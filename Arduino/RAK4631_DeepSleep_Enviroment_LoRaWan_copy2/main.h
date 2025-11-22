#include <Arduino.h>
#include <SPI.h>

#include <LoRaWan-RAK4630.h>

// Comment the next line if you want DEBUG output. But the power savings are not as good then!!!!!!!
#define MAX_SAVE

/* Time the device is sleeping in milliseconds = 1 minut * 60 seconds * 1000 milliseconds */
#define SLEEP_TIME 1 * 60 * 1000

// LoRaWan stuff
int8_t initLoRaWan(void);
bool sendLoRaFrame(float temp,float pressure, float humidity,float gas, float lati, float longi,float alti, float luz, float sismo_SI, float sismo_PGA, float particulas);
extern SemaphoreHandle_t loraEvent;

// Main loop stuff
void periodicWakeup(TimerHandle_t unused);
extern SemaphoreHandle_t taskEvent;
extern uint8_t rcvdLoRaData[];
extern uint8_t rcvdDataLen;
extern uint8_t eventType;
extern SoftwareTimer taskWakeupTimer;
