--- This file can be imported directly into mysql database or using mysql php management tool

CREATE TABLE IF NOT EXISTS `audit_log` (
  `auditlogid` int(10) NOT NULL auto_increment,
  `json_data` longtext NOT NULL,
  `remoteip` varchar(15) NOT NULL,
  `indate` varchar(30) NOT NULL,
  `client` int(1) default NULL COMMENT '0=web,1=python',
  `screcord` varchar(255) default NULL COMMENT 'Server Creation Record ID',
  PRIMARY KEY  (`auditlogid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `servers` (
  `serverid` varchar(30) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `ipaddress` varchar(15) NOT NULL,
  `macaddress` varchar(100) NOT NULL,
  `indate` varchar(30) NOT NULL,
  `lastupdate` varchar(30) NOT NULL,
  `active` int(1) NOT NULL default '1',
  PRIMARY KEY  (`serverid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;