#include "main.h"
#include <Wire.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME680.h> // Click to install library: http://librarymanager/All#Adafruit_BME680
#include "SparkFun_MLX90632_Arduino_Library.h"   // Click here to get the library: http://librarymanager/AllSparkFun_MLX90632_Arduino_Library
#include <SparkFun_u-blox_GNSS_Arduino_Library.h>
#include "UVlight_LTR390.h"
#include <RAK12027_D7S.h> // Click here to get the library: http://librarymanager/RAK12027_D7S
 
#define MLX90632_ADDRESS 0x3A
// Might need adjustments
#define SEALEVELPRESSURE_HPA (1010.0)
/** Semaphore used by events to wake up loop task */
SemaphoreHandle_t taskEvent = NULL;

/** Timer to wakeup task frequently and send message */
SoftwareTimer taskWakeupTimer;
MLX90632 RAK_TempSensor;
Adafruit_BME680 bme;
SFE_UBLOX_GNSS g_myGNSS;
UVlight_LTR390 ltr=UVlight_LTR390();
RAK_D7S D7S;

/** Buffer for received LoRaWan data */
uint8_t rcvdLoRaData[256];
/** Length of received data */
uint8_t rcvdDataLen = 0;
uint8_t eventType = -1;
float ALS = 0;  // Luz ambiente
float LUX = 0;  // Iluminância calculada
float UVS = 0;  // Radiação UV
float UVI = 0;  // Índice UV

  float temperature;
  float pressure;  
  float humidity ;
  float gas_resistance;
  long latitude;
  long longitude;
  long altitude;
  float luz ;
  float sism_si;
  float sism_pga;
float particulas;
long g_lastTime = 0; 

void allDataToSend(float temp, float pressure, float humidity, float gas_resistance, float luz, float sismo_SI, float sismo_PGA){
Serial.println("Enviando dados via LoRa:");
  Serial.print("Temperatura: "); Serial.print(temp);Serial.println(" *C");
  Serial.print("Pressão: "); Serial.print(pressure); Serial.println(" hPa");
  Serial.print("Umidade: "); Serial.print(humidity); Serial.println(" %");
  Serial.print("Resistência do Gás: "); Serial.print(gas_resistance); Serial.println(" KOhms");
  Serial.print("Luz Ambiente: "); Serial.println(luz);      

    Serial.print("sismo SI: ");
    Serial.print(D7S.getInstantaneusSI());  // Getting instantaneus SI.
    Serial.print(" [m/s]");

    Serial.print("      sismo PGA (Peak Ground Acceleration): ");
    Serial.print(D7S.getInstantaneusPGA()); // Getting instantaneus PGA.
    Serial.println(" [m/s^2]\n");

}
void sismos_init() {
  // Alimenta o D7S
  pinMode(WB_IO2, OUTPUT);
  digitalWrite(WB_IO2, LOW); 
  delay(1000);
  digitalWrite(WB_IO2, HIGH);
  
  // Inicia Serial e I2C
  Serial.begin(115200);
  // Aguarda o Serial (opcional)
  unsigned long timeout = millis();
  while (!Serial && millis() - timeout < 5000) {
    delay(100);
  }
  Serial.println("RAK12027 Seismograph initialization...");

  Wire.begin();  // usa pinos padrão ou Wire.begin(SDA, SCL);

  // Inicia o sensor
  while (!D7S.begin()) {
    Serial.print(".");
    delay(200);
  }
  Serial.println("\nD7S STARTED");

  // Configura eixo
  D7S.setAxis(SWITCH_AT_INSTALLATION);

  // Calibração inicial (sensor imóvel)
  Serial.println("Initializing the D7S sensor (keep it steady)...");
  delay(2000);
  D7S.initialize();

  // Aguarda até estar pronto
  while (!D7S.isReady()) {
    Serial.print(".");
    delay(500);
  }
  Serial.println("\nD7S INITIALIZED!");
}
void bme680_init()
{
  Wire.begin();

  if (!bme.begin(0x76)) {
    Serial.println("Could not find a valid BME680 sensor, check wiring!");
    return;
  }

  // Set up oversampling and filter initialization
  bme.setTemperatureOversampling(BME680_OS_8X);
  bme.setHumidityOversampling(BME680_OS_2X);
  bme.setPressureOversampling(BME680_OS_4X);
  bme.setIIRFilterSize(BME680_FILTER_SIZE_3);
  bme.setGasHeater(320, 150); // 320*C for 150 ms
}

void gps_init(){
   Serial1.begin(9600);
    delay(1000);  // Aguarde o GPS iniciar

    if (g_myGNSS.begin(Serial1)) {
        Serial.println("GPS conectado com sucesso!");
        g_myGNSS.setUART1Output(COM_TYPE_UBX);  // Apenas UBX
        g_myGNSS.setI2COutput(COM_TYPE_UBX);
        g_myGNSS.saveConfiguration();
    } else {
        Serial.println("ERRO: Falha ao conectar GPS.");
    }
}

void activateGPS() {
   Serial.println("Ativando GPS...");
    Serial1.begin(9600);  // Liga o GPS na UART1
    delay(1000);
}
void LTR390_Init(void)
{
  //Sensor power switch
  pinMode(WB_IO2, OUTPUT);
  digitalWrite(WB_IO2, HIGH);
  delay(300);

  Serial.println("RAK12019 test");
  Wire.begin();
  if ( ! ltr.init() ) {
    Serial.println("Couldn't find LTR sensor!");
    while (1) delay(10);
  }
  
  ltr.setMode(LTR390_MODE_ALS); //LTR390_MODE_UVS
	if (ltr.getMode() == LTR390_MODE_ALS)
	{
		Serial.println("In ALS mode");
	}
	
	ltr.setGain(LTR390_GAIN_3);
	ltr.setResolution(LTR390_RESOLUTION_16BIT);
	
	ltr.setThresholds(100, 1000); //Set the interrupt output threshold range for lower and upper.
	if (ltr.getMode() == LTR390_MODE_ALS)
	{
		ltr.configInterrupt(true, LTR390_MODE_ALS); //Configure the interrupt based on the thresholds in setThresholds()
	}
	
 
 Serial.println("Found LTR390 sensor!");
}

void periodicWakeup(TimerHandle_t unused)
{
	// Switch on blue LED to show we are awake
	digitalWrite(LED_BUILTIN, HIGH);
	eventType = 1;
	// Give the semaphore, so the loop task will wake up
	xSemaphoreGiveFromISR(taskEvent, pdFALSE);
}
void setup(void)
{
// Create the LoRaWan event semaphore
	taskEvent = xSemaphoreCreateBinary();
	// Initialize semaphore
	xSemaphoreGive(taskEvent);

	// Initialize the built in LED
	pinMode(LED_BUILTIN, OUTPUT);
	digitalWrite(LED_BUILTIN, LOW);

	// Initialize the connection status LED
	pinMode(LED_CONN, OUTPUT);
	digitalWrite(LED_CONN, HIGH);

#ifndef MAX_SAVE
	// Initialize Serial for debug output
	Serial.begin(115200);
  Serial1.begin(9600);

	time_t timeout = millis();
	// On nRF52840 the USB serial is not available immediately
	while (!Serial)
	{
		if ((millis() - timeout) < 5000)
		{
			delay(100);
			digitalWrite(LED_BUILTIN, !digitalRead(LED_BUILTIN));
		}
		else
		{
			break;
		}
	}
#endif

	digitalWrite(LED_BUILTIN, LOW);

#ifndef MAX_SAVE
	Serial.println("=====================================");
	Serial.println("RAK4631 LoRaWan Deep Sleep Test");
	Serial.println("=====================================");
#endif

	// Initialize LoRaWan and start join request
	int8_t loraInitResult = initLoRaWan();

#ifndef MAX_SAVE
	if (loraInitResult != 0)
	{
		switch (loraInitResult)
		{
		case -1:
			Serial.println("SX126x init failed");
			break;
		case -2:
			Serial.println("LoRaWan init failed");
			break;
		case -3:
			Serial.println("Subband init error");
			break;
		case -4:
			Serial.println("LoRa Task init error");
			break;
		default:
			Serial.println("LoRa init unknown error");
			break;
		}

		// Without working LoRa we just stop here
		while (1)
		{
			Serial.println("Nothing I can do, just loving you");
			delay(5000);
		}
	}
	Serial.println("LoRaWan init success");
#endif

  TwoWire &wirePort = Wire;
  MLX90632::status returnError;  
  //time_t timeout = millis();
  Serial.println("MLX90632 Read Example");
pinMode(WB_IO2, OUTPUT);
  digitalWrite(WB_IO2, 0);
  delay(1000);
  digitalWrite(WB_IO2, 1);
  delay(1000);

  Wire.begin(); //I2C init

  if (RAK_TempSensor.begin(MLX90632_ADDRESS, wirePort, returnError) == true) //MLX90632 init 
  {
     Serial.println("MLX90632 Init Succeed");
  }
  else
  {
     Serial.println("MLX90632 Init Failed");
  }

activateGPS();
  gps_init();
  bme680_init();
  sismos_init(); 
  if ( ! ltr.init() ) {
    Serial.println("Couldn't find LTR sensor!");
    while (1) delay(10);
  }
  
  ltr.setMode(LTR390_MODE_ALS); //LTR390_MODE_UVS
	if (ltr.getMode() == LTR390_MODE_ALS)
	{
		Serial.println("In ALS mode");
	}
	
	ltr.setGain(LTR390_GAIN_3);
	ltr.setResolution(LTR390_RESOLUTION_16BIT);
	
	ltr.setThresholds(100, 1000); //Set the interrupt output threshold range for lower and upper.
	if (ltr.getMode() == LTR390_MODE_ALS)
	{
		ltr.configInterrupt(true, LTR390_MODE_ALS); //Configure the interrupt based on the thresholds in setThresholds()
	}
	
 Serial.println("Found LTR390 sensor!");

	// Take the semaphore so the loop will go to sleep until an event happens
	xSemaphoreTake(taskEvent, 10);
}
void loop(void)
{
	// Switch off blue LED to show we go to sleep
	digitalWrite(LED_BUILTIN, LOW);

	// Sleep until we are woken up by an event
	if (xSemaphoreTake(taskEvent, portMAX_DELAY) == pdTRUE)
	{
		// Switch on blue LED to show we are awake
		digitalWrite(LED_BUILTIN, HIGH);
		delay(500); // Only so we can see the blue LED

		// Check the wake up reason
		switch (eventType)
		{
		case 0: // Wakeup reason is package downlink arrived
#ifndef MAX_SAVE
			Serial.println("Received package over LoRaWan");
#endif
			if (rcvdLoRaData[0] > 0x1F)
			{
#ifndef MAX_SAVE
				Serial.printf("%s\n", (char *)rcvdLoRaData);
#endif
			}
			else
			{
#ifndef MAX_SAVE
				for (int idx = 0; idx < rcvdDataLen; idx++)
				{
					Serial.printf("%X ", rcvdLoRaData[idx]);
				}
				Serial.println("");
#endif
			}

			break;
		case 1: // Wakeup reason is timer
#ifndef MAX_SAVE
			Serial.println("Timer wakeup");
#endif
			/// \todo read sensor or whatever you need to do frequently
  //Enviroment
  if (! bme.performReading())
  {
    Serial.println("Failed to perform reading :(");
  }
 		// Lê dados ambientais
  temperature = bme.temperature;
  pressure = bme.pressure / 100.0;  // Convertendo para hPa
  humidity = bme.humidity;
  gas_resistance = bme.gas_resistance / 1000.0; // Convertendo para kΩ

  // Lê dados do GPS
  latitude = g_myGNSS.getLatitude();
  longitude = g_myGNSS.getLongitude();
  altitude = g_myGNSS.getLatitude();

  // Determina qual valor de luz enviar
 ltr.setMode(LTR390_MODE_ALS);
delay(100);
  if (ltr.newDataAvailable()) {
    luz = ltr.getLUX();
    Serial.printf("Lux: %.2f\n", luz);
  }else {
  Serial.println("Sem novos dados de luz.");
}
 sism_si=D7S.getInstantaneusSI();
sism_pga=D7S.getInstantaneusPGA();
// particulas ficticio
particulas=20;
    	// Send the data package
allDataToSend(temperature, pressure, humidity, gas_resistance, luz, sism_si, sism_pga);
	if (sendLoRaFrame(temperature, pressure, humidity, gas_resistance, latitude, longitude, altitude, luz, sism_si, sism_pga,particulas)) 
 
 			{
        
#ifndef MAX_SAVE
				Serial.println("Lora envia dados com sucesso!");
#endif
			}
			else
			{
#ifndef MAX_SAVE
				Serial.println("LoRaWan: Erro ao enviar dados via LoRa.");
				/// \todo maybe you need to retry here?
#endif
			}

			break;
		default:
#ifndef MAX_SAVE
			Serial.println("This should never happen ;-)");
#endif
			break;
		}
   
   digitalWrite(LED_BUILTIN, LOW);
		// Go back to sleep
		xSemaphoreTake(taskEvent, 10);
	}
}
