# Use the following in your crontab tab to schedule the automatic check process
*/5 * * * * python /path/to/client.py >> /path/to/client.log


So far, tested on Python 2.4 (CentOS 5) and 2.6.6 (CentOS 6).