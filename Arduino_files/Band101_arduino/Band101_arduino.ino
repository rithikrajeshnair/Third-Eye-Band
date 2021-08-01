//Target board: Nodemcu
// Importing required libraries
#include <TinyGPS.h>
#include <ESP8266WiFi.h>
#include <SoftwareSerial.h>

// Setting up pin numbers for connection
#define temp_pulse_pin A0
#define temp_VCC D0
#define pulse_VCC D1
#define gps_RX D6
#define gps_TX D5
SoftwareSerial gps_serial(gps_TX, gps_RX); //Nodemcu receives from gps_TX and sends to gps_RX
TinyGPS gps;

// Variables to store sensor values
int BPM = 0;
float temperature, gps_latitude, gps_longitude;

// Setting up wifi connectivity credentials
const char* ssid = "wifi_name";
const char* password = "wifi_password";
const char* hostName = "address";
const short int hostPort = 80; //for http protocol

void initializeSensors();     //Initialise sensors
void read_Temperature();       //Reads the temperature
void readPulse();             //Reads the Pulse BPM
void readGPS();               //Reads the GPS location


String dataPushFormat() {     //Format to push the data to web
  return String("GET http://band101.hackp.cyberdome.org.in/arduino_connect.php?") +
         "&MAC=" + WiFi.macAddress() +
         "&temperature=" + temperature + "&BPM=" + BPM +
         "&latitude=" + gps_latitude + "&longitude=" + gps_longitude +
         " HTTP/1.1\r\n" + "Host: " + hostName + "\r\n" + "Connection: close\r\n\r\n" ;
}

void setup() {
  Serial.begin(115200);
  Serial.println("\nInitiating device...");

  WiFi.mode(WIFI_OFF);        //Prevents reconnection issue (taking too long to connect)
  delay(1000);
  WiFi.mode(WIFI_STA);        //Hides the viewing of ESP as wifi hotspot

  // Connecting to network. Waits for connection
  Serial.print("Connecting to network : "); Serial.print(ssid);
  WiFi.begin(ssid, password);

  //Comment while loop to prevent waiting to connect
  while (WiFi.status() != WL_CONNECTED) {
    delay(500); Serial.print(".");
  }

  //Wifi connection successful
  Serial.print("\nDevice connected to "); Serial.println(ssid);
  Serial.print("Connect to this device at "); Serial.println((WiFi.localIP().toString()));

  //Activate sensors
  initializeSensors();

  Serial.println("Device initiated");
}

void loop() {

  delay(3000);
  Serial.println("\n\n");
  for (int i = 0; i < 10; i++)Serial.print("*****");

  //Checking for active connection.
  Serial.print("\nWifi status : connection ");
  Serial.println( (WiFi.status() != WL_CONNECTED ? "lost" : "active") );

  //Read the data from sensors
  Serial.println("\nReading sensors");
  read_Temperature();
  readPulse();
  readGPS();

  //Uploading data to server
  Serial.print("\nUploading data to server\n\tConnecting to "); Serial.println(hostName);

  // WifiClient object to establish TCP connetion
  WiFiClient my_client;
  // Checking for connection with hostName at hostPort
  if (!my_client.connect(hostName, hostPort)) {
    Serial.print("\tCannot connect to ");
    Serial.print(hostName); Serial.print(" at "); Serial.println(hostPort);
    return;
  }
  Serial.print("\tConnection success for ");
  Serial.print(hostName); Serial.print(" at "); Serial.println(hostPort);

  // Sending data to the server
  my_client.print(dataPushFormat());

  // Check for client timeout
  unsigned long timeout = millis();
  while (my_client.available() == 0) {
    if (millis() - timeout > 1000) {
      Serial.println("\tClient Timeout");
      delay(1000);
      return;
    }
  }

  // Read responce from server
  Serial.print("Responce from Server\n\t");
  while (my_client.available()) Serial.print(my_client.readStringUntil('\r'));
}

void initializeSensors() {
  pinMode(temp_pulse_pin, INPUT);
  pinMode(temp_VCC, OUTPUT);
  pinMode(pulse_VCC, OUTPUT);
  digitalWrite(temp_VCC, LOW);
  digitalWrite(pulse_VCC, LOW);

  //GPS module
  gps_serial.begin(9600);
}


void read_Temperature() {
  Serial.println("Sensor LM35 : Reading temperature...");
  digitalWrite(temp_VCC, HIGH);
  delay(2000);
  temperature = analogRead(temp_pulse_pin) * 0.3223;
  Serial.print("              Current temp "); Serial.print(temperature); Serial.println(" degree celsius");
  digitalWrite(temp_VCC, LOW);
}

void readPulse() {
  Serial.println("Sensor Pulse: Detecting Heart beat...");
  digitalWrite(pulse_VCC, HIGH); delay(1000);

  const unsigned short Signal_buffer_size = 5;
  const unsigned short beat_rising_threshold = 5;

  bool first_beat = true, is_beat = false;
  unsigned int Signal_count;
  unsigned short Signal_buffer_ptr, beat_rising = 0, beat_count = 0;
  unsigned long first_beat_time, last_beat_time, Signal_read_start;
  float Signal, Signal_buffer_sum = 0.0, Signal_buffer[Signal_buffer_size];
  float current_beat = 0.0, previous_beat = 0.0;

  for (Signal_buffer_ptr = 0; Signal_buffer_ptr < Signal_buffer_size; Signal_buffer_ptr++)
    Signal_buffer[Signal_buffer_ptr] = 0.0;
  Signal_buffer_ptr = 0;

  unsigned long beat_monitor_start = millis();
  while (millis() - beat_monitor_start < 10000) {
    yield();
    Signal = 0.0;
    Signal_count = 0;
    Signal_read_start = millis();
    while (millis() - Signal_read_start < 10) {
      Signal += analogRead(temp_pulse_pin);
      Signal_count++;
    }
    Signal /= Signal_count;

    Signal_buffer_sum = Signal_buffer_sum - Signal_buffer[Signal_buffer_ptr] + Signal;
    Signal_buffer[Signal_buffer_ptr++] = Signal;
    Signal_buffer_ptr %= Signal_buffer_size;
    current_beat = Signal_buffer_sum / Signal_buffer_size;

    if (current_beat <= previous_beat) {
      is_beat = false;
      beat_rising = 0;
    }
    else if (!is_beat) {
      if (++beat_rising >= beat_rising_threshold) {
        is_beat = true;

        if (!first_beat) {
          beat_count++;
          Serial.print("#");
          last_beat_time = millis();
        }
        else {
          first_beat = false;
          first_beat_time = millis();
        }
      }
    }
    previous_beat = current_beat;
  }

  BPM = (beat_count * 60000) / (last_beat_time - first_beat_time);
  Serial.print("\n              BPM = "); Serial.println(BPM/2);
  digitalWrite(pulse_VCC, LOW);
}

void readGPS() {
  Serial.println("GPS module :  Reading GPS...");

  unsigned long Start = millis();
  do {
    while (gps_serial.available()) gps.encode(gps_serial.read());
  } while (millis() - Start < 5000 );

  unsigned long chars = 0;
  unsigned short sentences = 0, failed = 0;
  gps.stats(&chars, &sentences, &failed);
  if (chars == 0xFFFFFFFF || sentences == 0xFFFFFFFF || failed == 0xFFFFFFFF)
    Serial.println("              GPS did not respond!");

  unsigned long position_fix, Satellites, Hdop;
  Satellites = gps.satellites();
  Hdop = gps.hdop();
  gps.f_get_position(&gps_latitude, &gps_longitude, &position_fix);

  Serial.print("              Satellites   : ");
  if (Satellites == TinyGPS::GPS_INVALID_SATELLITES) Serial.println("invalid");
  else Serial.println(Satellites);

  Serial.print("              HDOP         : ");
  if (Hdop == TinyGPS::GPS_INVALID_HDOP ) Serial.println("invalid");
  else Serial.println(Hdop);

  Serial.print("              Latitude     : ");
  if (gps_latitude == TinyGPS::GPS_INVALID_F_ANGLE) { Serial.println("invalid"); gps_latitude=0.0;}
  else Serial.println(gps_latitude);

  Serial.print("              Longitude    : ");
  if (gps_longitude == TinyGPS::GPS_INVALID_F_ANGLE) { Serial.println("invalid"); gps_longitude=0.0;}
  else Serial.println(gps_longitude);

  Serial.print("              Position fix : ");
  if (position_fix == TinyGPS::GPS_INVALID_AGE) Serial.println("invalid");
  else Serial.println(position_fix);
}
