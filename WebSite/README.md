## Third-Eye-Band-web

Provides with a web interface to easily visualize and track the movement. Makes use of data collected from the associated hardware unit.


Pre-requisites for locally hosting

1) phpmyadmin and xampp should be installed

2) create a database and table in it according to the details in the php script 
        (the default databasename is 'homeleafletdb' and table 'worlddata' and columns 'id','name','homelat','homelng','latitude','longitude','heartbeat','bodytemp')
          if a databasename and tablename is of your choice change it accordingly in the php script of 'index.php' and 'connect.php' files

3)Now locally host 'myfile.json' in the local server and copy its url and paste it in lines 182 and 216 of the 'index.php'.

4)Then locally host the 'index.php' file.
