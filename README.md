# rpi-thermostat

Using a ds18b20 on 3v3,GPIO 4,GND
and an opto isolated 5v relay on GPIO 14

to install-
Add the following line to /boot/config.txt
```bash
dtoverlay=w1-gpio
```
and run-
```bash
sudo apt-get install apache2 php5 git
sudo modprobe w1-gpio
sudo modprobe w1-therm
cd /var/www/html
git clone https://github.com/acidsugar79/rpi-thermostat.git
```

designed with a smartphone in portrait

![preview](https://raw.githubusercontent.com/acidsugar79/rpi-thermostat/master/preview.png)
![preview](https://raw.githubusercontent.com/acidsugar79/rpi-thermostat/master/preview1.png)
![preview](https://raw.githubusercontent.com/acidsugar79/rpi-thermostat/master/preview2.png)
