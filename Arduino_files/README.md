## Arduino hardware
Arduino is an open community that promotes and supports the use of Arduino boards and modules. Arduino finds its extensive usage in various fields, especially in the areas of micro-controlled digital circuits and IoT. Here, Arduino is used to collect data, so as to support the software requirement of this project. The data's are pushed to internet.

#### Running the code
* Install Arduino IDE from https://www.arduino.cc/en/Main/Software
* Install Nodemcu board. For more info refer: https://circuits4you.com/2018/06/21/add-nodemcu-esp8266-to-arduino-ide/
* Install Required libraries. For more info refer: https://www.arduino.cc/en/Guide/Libraries
* In band101_arduino.ino, update your wifi credentials and server credentials.
* Complete circuit connections and then upload code band101_arduino.ino to Nodemcu

#### Libraries required
* TinyGPS : https://github.com/mikalhart/TinyGPSPlus
* SoftwareSerial : https://github.com/PaulStoffregen/SoftwareSerial

#### Arduino boards and modules used
* Microcontroller : Nodemcu V1.0
* GPS module : u-blox NEO-6M
* Temperature sensor : LM35
* Heartbeat sensor
* 74HC4051 IC (multiplexer/demultiplexer)

#### Connection info
Temperature sensor: LM35
* LM35(VCC) -> Nodemcu(3.3V)
* LM35(OUT) -> 74HC4051(15)
* LM35(GND) -> Nodemcu(GND)

Heart beat pulse sensor
* pulse(VCC) -> Nodemcu(3.3V)
* pulse(OUT) -> 74HC4051(1)
* pulse(GND) -> Nodemcu(GND)

GPS module: u-blox NEO-6M
* NEO-6M(VCC) -> Nodemcu(3.3V)
* NEO-6M(RX)  -> Nodemcu(D6)
* NEO-6M(TX)  -> Nodemcu(D5)
* NEO-6M(GND) -> Nodemcu(GND)

74HC4051 IC:
* 74HC4051(1)  -> pulse(OUT)
* 74HC4051(2)  -> not connected
* 74HC4051(3)  -> Nodemcu(A0)
* 74HC4051(4)  -> Nodemcu(GND)
* 74HC4051(5)  -> Nodemcu(GND)
* 74HC4051(6)  -> Nodemcu(GND)
* 74HC4051(7)  -> Nodemcu(GND)
* 74HC4051(8)  -> Nodemcu(GND)
* 74HC4051(9)  -> Nodemcu(GND)
* 74HC4051(10) -> Nodemcu(D0)
* 74HC4051(11) -> Nodemcu(D1)
* 74HC4051(12) -> Nodemcu(GND)
* 74HC4051(13) -> Nodemcu(GND)
* 74HC4051(14) -> Nodemcu(GND)
* 74HC4051(15) -> LM35(OUT)
* 74HC4051(16) -> Nodemcu(3.3V)
