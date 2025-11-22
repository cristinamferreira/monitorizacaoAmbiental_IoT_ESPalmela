#include "main.h"

/** DIO1 GPIO pin for RAK4631 */
#define PIN_LORA_DIO_1 47

/** Max size of the data to be transmitted. */
#define LORAWAN_APP_DATA_BUFF_SIZE 64
/** Number of trials for the join request. */
#define JOINREQ_NBTRIALS 8

/** Lora application data buffer. */
static uint8_t m_lora_app_data_buffer[LORAWAN_APP_DATA_BUFF_SIZE];
/** Lora application data structure. */
static lmh_app_data_t m_lora_app_data = {m_lora_app_data_buffer, 0, 0, 0, 0};

// LoRaWan event handlers
/** LoRaWan callback when join network finished */
static void lorawan_has_joined_handler(void);
/** LoRaWan callback when join failed */
static void lorawan_join_failed_handler(void);
/** LoRaWan callback when data arrived */
static void lorawan_rx_handler(lmh_app_data_t *app_data);
/** LoRaWan callback after class change request finished */
static void lorawan_confirm_class_handler(DeviceClass_t Class);
/** LoRaWan Function to send a package */
bool sendLoRaFrame(float temp, float pressure, float humidity, float gas, float lati, float longi,float alti, float luz, float sismo_SI, float sismo_PGA, float particulas);

static lmh_param_t lora_param_init = {LORAWAN_ADR_OFF, DR_3, LORAWAN_PUBLIC_NETWORK, JOINREQ_NBTRIALS, LORAWAN_DEFAULT_TX_POWER, LORAWAN_DUTYCYCLE_OFF};

/** Structure containing LoRaWan callback functions, needed for lmh_init() */
static lmh_callback_t lora_callbacks = {BoardGetBatteryLevel, BoardGetUniqueId, BoardGetRandomSeed,
                                        lorawan_rx_handler, lorawan_has_joined_handler, lorawan_confirm_class_handler, lorawan_join_failed_handler
                                       };
//  !!!! KEYS ARE MSB !!!!
/** Device EUI required for OTAA network join */
uint8_t nodeDeviceEUI[8] = {0xAC, 0x1F, 0x09, 0xFF, 0xFE, 0x18, 0x49, 0x12};
/** Application EUI required for network join */
uint8_t nodeAppEUI[8] = {0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0xAC};
/** Application key required for network join */
uint8_t nodeAppKey[16] = {0x39, 0x8D, 0x6B, 0x47, 0xB5, 0x02, 0x2C, 0xF3, 0xB0, 0xE9, 0x09, 0x06, 0xA2, 0x3C, 0x5A, 0xD7};
/** Device address required for ABP network join */
uint32_t nodeDevAddr = 0x26021FB6;
/** Network session key required for ABP network join */
uint8_t nodeNwsKey[16] = {0x32, 0x3D, 0x15, 0x5A, 0x00, 0x0D, 0xF3, 0x35, 0x30, 0x7A, 0x16, 0xDA, 0x0C, 0x9D, 0xF5, 0x3F};
/** Application session key required for ABP network join */
uint8_t nodeAppsKey[16] = {0x3F, 0x6A, 0x66, 0x45, 0x9D, 0x5E, 0xDC, 0xA6, 0x3C, 0xBC, 0x46, 0x19, 0xCD, 0x61, 0xA1, 0x1E};

/** Flag whether to use OTAA or ABP network join method */
bool doOTAA = true;

DeviceClass_t gCurrentClass = CLASS_A;                  /* class definition*/
LoRaMacRegion_t gCurrentRegion = LORAMAC_REGION_EU868;  /* Region:EU868*/

/**
   @brief Initialize LoRa HW and LoRaWan MAC layer

   @return int8_t result
    0 => OK
   -1 => SX126x HW init failure
   -2 => LoRaWan MAC initialization failure
   -3 => Subband selection failure
*/
int8_t initLoRaWan(void)
{
  // Initialize LoRa chip.
  if (lora_rak4630_init() != 0)
  {
    return -1;
  }

  // Setup the EUIs and Keys
  if (doOTAA)
  {
    lmh_setDevEui(nodeDeviceEUI);
    lmh_setAppEui(nodeAppEUI);
    lmh_setAppKey(nodeAppKey);
  }
  else
  {
    lmh_setNwkSKey(nodeNwsKey);
    lmh_setAppSKey(nodeAppsKey);
    lmh_setDevAddr(nodeDevAddr);
  }

  // Initialize LoRaWan
  if (lmh_init(&lora_callbacks, lora_param_init, doOTAA, gCurrentClass, gCurrentRegion) != 0)
  {
    return -2;
  }

  // For some regions we might need to define the sub band the gateway is listening to
  // This must be called AFTER lmh_init()
  if (!lmh_setSubBandChannels(1))
  {
    return -3;
  }

  // Start Join procedure
#ifndef MAX_SAVE
  Serial.println("Start network join request");
#endif
  lmh_join();

  return 0;
}

/**
   @brief LoRa function for handling HasJoined event.
*/
static void lorawan_has_joined_handler(void)
{
  if (doOTAA)
  {
    uint32_t otaaDevAddr = lmh_getDevAddr();
#ifndef MAX_SAVE
    Serial.printf("OTAA joined and got dev address %08X\n", otaaDevAddr);
#endif
  }
  else
  {
#ifndef MAX_SAVE
    Serial.println("ABP joined");
#endif
  }

  digitalWrite(LED_CONN, LOW);
  
  // Now we are connected, start the timer that will wakeup the loop frequently
  taskWakeupTimer.begin(SLEEP_TIME, periodicWakeup);
  taskWakeupTimer.start();
}
static void lorawan_join_failed_handler(void)
{
  Serial.println("OVER_THE_AIR_ACTIVATION failed!");
  Serial.println("Check your EUI's and Keys's!");
  Serial.println("Check if a Gateway is in range!");
}
static void lorawan_rx_handler(lmh_app_data_t *app_data)
{
#ifndef MAX_SAVE
  Serial.printf("LoRa Packet received on port %d, size:%d, rssi:%d, snr:%d\n",
                app_data->port, app_data->buffsize, app_data->rssi, app_data->snr);
#endif
  switch (app_data->port)
  {
    case 3:
      // Port 3 switches the class
      if (app_data->buffsize == 1)
      {
        switch (app_data->buffer[0])
        {
          case 0:
            lmh_class_request(CLASS_A);
#ifndef MAX_SAVE
            Serial.println("Request to switch to class A");
#endif
            break;

          case 1:
            lmh_class_request(CLASS_B);
#ifndef MAX_SAVE
            Serial.println("Request to switch to class B");
#endif
            break;

          case 2:
            lmh_class_request(CLASS_C);
#ifndef MAX_SAVE
            Serial.println("Request to switch to class C");
#endif
            break;

          default:
            break;
        }
      }
      break;
    case LORAWAN_APP_PORT:
      // Copy the data into loop data buffer
      memcpy(rcvdLoRaData, app_data->buffer, app_data->buffsize);
      rcvdDataLen = app_data->buffsize;
      eventType = 0;
      // Notify task about the event
      if (taskEvent != NULL)
      {
#ifndef MAX_SAVE
        Serial.println("Waking up loop task");
#endif
        xSemaphoreGive(taskEvent);
      }
  }
}

static void lorawan_confirm_class_handler(DeviceClass_t Class)
{
#ifndef MAX_SAVE
  Serial.printf("switch to class %c done\n", "ABC"[Class]);
#endif

  // Informs the server that switch has occurred ASAP
  m_lora_app_data.buffsize = 0;
  m_lora_app_data.port = LORAWAN_APP_PORT;
  lmh_send(&m_lora_app_data, LMH_UNCONFIRMED_MSG);
}

/**
   @brief Send a LoRaWan package

   @return result of send request
*/
bool sendLoRaFrame(float temp,float pressure, float humidity,float gas, float lati, float longi,float alti, float luz, float sismo_SI, float sismo_PGA, float particulas)
{
  if (lmh_join_status_get() != LMH_SET)
  {
    //Not joined, try again later
#ifndef MAX_SAVE
    Serial.println("Did not join network, skip sending frame");
#endif
    return false;
  }

  m_lora_app_data.port = LORAWAN_APP_PORT;

  //******************************************************************
  /// \todo here some more usefull data should be put into the package
  //******************************************************************

   uint8_t buffSize = 0;
 uint16_t tempA = (temp*100)+5000;
 uint16_t pressA=pressure*10;
 uint16_t humA = (humidity*100)+5000; 
uint16_t gsA=gas*100;     
// Reset buffer size
 buffSize = 0;
// Adding temperature
m_lora_app_data_buffer[buffSize++] = 0x01;
m_lora_app_data_buffer[buffSize++] = (uint8_t)(tempA >> 8);  // High byte
m_lora_app_data_buffer[buffSize++] = (uint8_t)(tempA & 0xFF); // Low byte

// Adding pressure
m_lora_app_data_buffer[buffSize++] = 0x02;
m_lora_app_data_buffer[buffSize++] = (uint8_t)(pressA >> 8);
m_lora_app_data_buffer[buffSize++] = (uint8_t)(pressA & 0xFF);

// Adding humidity data
m_lora_app_data_buffer[buffSize++] = 0x03;
m_lora_app_data_buffer[buffSize++] = (uint8_t)(humA >> 8);
m_lora_app_data_buffer[buffSize++] = (uint8_t)(humA & 0xFF);

// Adding gas data
m_lora_app_data_buffer[buffSize++] = 0x04;
m_lora_app_data_buffer[buffSize++] = (uint8_t)(gsA >> 8);
m_lora_app_data_buffer[buffSize++] = (uint8_t)(gsA & 0xFF);
int32_t lat = (int32_t)(lati * 10000);
m_lora_app_data_buffer[buffSize++] = 0x05;
m_lora_app_data_buffer[buffSize++] = (lat >> 16) & 0xFF;
m_lora_app_data_buffer[buffSize++] = (lat >> 8) & 0xFF;
m_lora_app_data_buffer[buffSize++] = lat & 0xFF;

int32_t lon = (int32_t)(longi * 10000);  
m_lora_app_data_buffer[buffSize++] = 0x06;
m_lora_app_data_buffer[buffSize++] = (lon >> 16) & 0xFF;  
m_lora_app_data_buffer[buffSize++] = (lon >> 8) & 0xFF;
m_lora_app_data_buffer[buffSize++] = lon & 0xFF;

int16_t altInt = (int16_t)(alti);  
m_lora_app_data_buffer[buffSize++] = 0x07;
m_lora_app_data_buffer[buffSize++] = (altInt >> 8) & 0xFF;
m_lora_app_data_buffer[buffSize++] = altInt & 0xFF;

int16_t luzInt = (int16_t)(luz);
m_lora_app_data_buffer[buffSize++] = 0x08;
m_lora_app_data_buffer[buffSize++] = (luzInt >> 8) & 0xFF;
m_lora_app_data_buffer[buffSize++] = luzInt & 0xFF;
int16_t sismo_SI_Int = (int16_t)(sismo_SI);

m_lora_app_data_buffer[buffSize++] = 0x09;
m_lora_app_data_buffer[buffSize++] = (sismo_SI_Int >> 8) & 0xFF;
m_lora_app_data_buffer[buffSize++] = sismo_SI_Int & 0xFF;
int16_t sismo_PGA_Int = (int16_t)(sismo_PGA);
m_lora_app_data_buffer[buffSize++] = 0x10;
m_lora_app_data_buffer[buffSize++] = (sismo_PGA_Int >> 8) & 0xFF;
m_lora_app_data_buffer[buffSize++] = sismo_PGA_Int & 0xFF;
int16_t particulas_Int = (int16_t)(particulas);
m_lora_app_data_buffer[buffSize++] = 0x11;
m_lora_app_data_buffer[buffSize++] = (particulas_Int >> 8) & 0xFF;
m_lora_app_data_buffer[buffSize++] = particulas_Int & 0xFF;

Serial.print("Como vai o gas = ");Serial.println(gsA);Serial.print("Como vai o press = ");Serial.println(pressA);
Serial.print("Como vai o sism_SI = ");Serial.println(sismo_SI_Int);Serial.print("Como vai o sism_PGA = ");Serial.println(sismo_PGA_Int/1000);
Serial.print("Como vai o particulas = ");Serial.println(particulas_Int);

m_lora_app_data.buffsize = buffSize;

Serial.print("Payload: ");
for (int i = 0; i < buffSize; i++) {
    Serial.print(m_lora_app_data_buffer[i], HEX);
    Serial.print(" ");
}
Serial.println();
  lmh_error_status error = lmh_send(&m_lora_app_data, LMH_UNCONFIRMED_MSG);

  return (error == 0);
}
