# External module imports
import RPi.GPIO as GPIO

# Pin Definitons:
ledPin = 17
relayPin = 14

# Pin Setup:
GPIO.setmode(GPIO.BCM) # Broadcom pin-numbering scheme
GPIO.setup(ledPin, GPIO.OUT) # LED pin set as output
GPIO.setup(relayPin, GPIO.OUT)

# Initial state for LEDs:
GPIO.output(ledPin, GPIO.LOW)
GPIO.output(relayPin, GPIO.LOW)
