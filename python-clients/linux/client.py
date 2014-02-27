import os, webbrowser, socket
import sys, urllib2, urllib
import StringIO
import traceback
import commands
import datetime
import time

url = "https://bizzarnet.com/watchtower/CommandCenter.php"

try:

    hostname = socket.gethostname()
    print "Host: " + hostname
    ipaddress = commands.getoutput("/sbin/ifconfig").split("\n")[1].split()[1][5:]
    print "IP: " + ipaddress
    macaddress = commands.getoutput("/sbin/ifconfig").split("\n")[0].split()[4][:]
    print "MAC: " + macaddress

    values = {"action":"checkin","hostname":hostname,"ipaddress":ipaddress,"macaddress":macaddress}
    data = urllib.urlencode(values)
    req = urllib2.Request(url, data)
    response = urllib2.urlopen(req)
    the_page = response.read()

    now = datetime.datetime.now()
    print "Server checked in at: " + now.strftime("%m/%d/%Y %H:%M:%S")

    # TODO: look into how it handles servers with multiple network interfaces...
    # make sure getting eth0 results for server fingerpring

    # 1. Do server checkin...
    # 2. Check if there are any service restart requests
    # 3. Check if there are any log file requests
    # ...
    # 5. profit!

except Exception, e:
    print "There was an error!"
    print e

print "#################################################################"
