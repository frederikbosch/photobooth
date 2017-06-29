HITI_HD="yxz"
HITI_GREP="Hi"
HITI_DEVICE=$(lsusb |grep $HITI_GREP |grep -Eo 'Device ([0-9]{1,3})' |grep -Eo '[0-9]{1,3}')
HITI_BUS=$(lsusb |grep $HITI_GREP |grep -Eo 'Bus ([0-9]{1,3})' |grep -Eo '[0-9]{1,3}')
/media/$HITI_HD/usbreset /dev/bus/usb/$HITI_BUS/$HITI_DEVICE
lpr -o ppi=300 -o document-format=image/jpeg $1

