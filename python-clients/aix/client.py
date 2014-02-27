import uuid, os, webbrowser
import sys, urllib2, urllib
import StringIO
import traceback
import wmi
from _winreg import (HKEY_LOCAL_MACHINE, KEY_ALL_ACCESS, OpenKey, EnumValue, QueryValueEx)

from SystemInfo import SystemInformation

url     = "http://www.grademypc.com/submit/postdata.php"
posturl = "http://www.grademypc.com/report/?id="

version = "1.0"
license = "License.txt"
report  = "ScanResults.log"

###################################################################
# DO NOT EDIT BELOW THIS POINT UNLESS YOU KNOW WHAT YOUR DOING!!! #
###################################################################
separator = "*" * 80
softFile = open(report, 'w')

#TODO: Do I need both or can these be combined??
c = wmi.WMI()
r = wmi.WMI(namespace="DEFAULT").StdRegProv

#GET LIST OF LOGICAL DRIVE LETTERS, TYPES, AND FREE SPACE
DRIVE_TYPES={
  0 : "Unknown",
  1 : "No Root Directory",
  2 : "Removable Disk",
  3 : "Local Disk",
  4 : "Network Drive",
  5 : "Compact Disc",
  6 : "RAM Disk"
}
softFile.write('SECTION:THEBASICS\n')
print 'OS:', SystemInformation.os() 

softFile.write('SECTION:LOGICALDRIVES\n')
softFile.write(separator + '\n')
for d in c.Win32_LogicalDisk():
    driveletter = d.Caption
    drivetype = DRIVE_TYPES[d.DriveType]
    if d.DriveType==3:
        drivefreespace = "%0.2f%% free" % (100.0 * long (d.FreeSpace) / long (d.Size))
    else:
        drivefreespace = "unknown"
    softFile.write(driveletter + '|Type: ' + drivetype + '|Free Space: ' + drivefreespace + '\n')
softFile.write(separator + '\n')

#GET LIST OF INSTALLED APPLICATIONS (PROGRAMS IN ADD/REMOVE PROGRAMS)
softFile.write('SECTION:INSTALLEDAPPS\n')
keyPath = r"Software\Microsoft\Windows\CurrentVersion\Uninstall"
result, names = r.EnumKey(hDefKey=HKEY_LOCAL_MACHINE, sSubKeyName=r"Software\Microsoft\Windows\CurrentVersion\Uninstall")
for subkey in names:
    try:
        softFile.write(separator + '\n')
        path = keyPath + "\\" + subkey
        key = OpenKey(HKEY_LOCAL_MACHINE, path, 0, KEY_ALL_ACCESS)
        try:
            temp = QueryValueEx(key, 'DisplayName')
            display = str(temp[0])
            softFile.write('Display Name: ' + display + '\nRegkey: ' + subkey + '\n')
        except:
            softFile.write('Regkey: ' + subkey + '\n') 
    
    except:
        fp = StringIO.StringIO()
        traceback.print_exc(file=fp)
        errorMessage = fp.getvalue()
        error = 'Error for ' + path + '. Message follows:\n' + errorMessage
        softFile.write(error)
        softFile.write("\n\n")
softFile.write(separator + '\n')

#SHOW ALL RUNNING PROCESSES
softFile.write("SECTION:PROCESSES\n")
softFile.write(separator + '\n')
for process in c.Win32_Process ():
    processid = str(process.ProcessId)
    processname = str(process.Name)
    commandline = str(process.CommandLine)
    softFile.write("Process: " + processname + "\n")
    softFile.write("Command: " + commandline + "\n")
    softFile.write(separator + '\n')

#SHOW ALL SERVICES
softFile.write("SECTION:SERVICES\n")
softFile.write(separator + '\n')
services = c.Win32_Service()
if services:
    for s in services:
        caption = str(s.Caption)
        startmode = str(s.StartMode)
        state = str(s.State)
        status = str(s.Status)
        softFile.write(caption + "|StartMode: " + startmode + "|CurrentState: " + state + "|Status: " + status + "\n")
else:
    softFile.write("No services? How did you get that??\n")
softFile.write(separator + '\n')

#SHOW SERVICES WITH AUTOMATIC START THAT ARE NOT RUNNING (ERROR?)
softFile.write("SECTION:AUTOSERVICESSTOPPED\n")
stopped_services = c.Win32_Service (StartMode="Auto", State="Stopped")
if stopped_services:
    for s in stopped_services:
        scaption = s.Caption
        softFile.write(scaption + " service is not running\n")
else:
    softFile.write("No auto services stopped\n")
softFile.write(separator + '\n')

#CLOSE FILE POINTER
softFile.close()

#IF LICENSE FILE EXISTS, READ KEY
#IF LICENSE FILE DOES NOT EXIST, CREATE EMPTY FILE
try:
    licenseFile = open(license, 'r')
    licensestring = licenseFile.read()
except:
    licenseFile = open(license, 'w')
    licensestring = ""

if(len(licensestring)>1):
    #USE EXISTING KEY
    #print "Using key... " + key
    key = str(licensestring)
else:
    #GENERATE A NEW KEY
    #print "Generating new key... " + key
    key = str(uuid.uuid4()).upper() #Random key generation
    createkey = open(license, 'w').write(key)

try:
    reportstring = open(report, 'r').read()
    reportdata = str(reportstring)
    values = {'report' : reportdata, 'license' : key }
    data = urllib.urlencode(values)
    req = urllib2.Request(url, data)
    response = urllib2.urlopen(req)
    the_page = response.read()
    print the_page
    print "Report has been submitted!"
    webbrowser.open(posturl + key)
except Exception, e:
    print "There was an error!"
    print e