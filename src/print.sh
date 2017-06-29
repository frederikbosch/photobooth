HITI_HD="/media/dc7aa23f-297e-43c3-a1f4-64765c52c43c"
HITI_GREP="Hi"
HITI_DEVICE=$(lsusb |grep $HITI_GREP |grep -Eo 'Device ([0-9]{1,3})' |grep -Eo '[0-9]{1,3}')
HITI_BUS=$(lsusb |grep $HITI_GREP |grep -Eo 'Bus ([0-9]{1,3})' |grep -Eo '[0-9]{1,3}')
$HITI_HD/home/usbreset /dev/bus/usb/$HITI_BUS/$HITI_DEVICE
lpr -o ppi=300 -o document-format=image/jpeg -o PageSize=P6x4 -o page-border=none $1

