# ComicDB
#### ComicDB is a self-hosted personal comics library. It is brower-based, you can view and download your comics collection on your PC, smartphone or tablet. It can handle about any digital comic book format as it only links to your comic files.

#### ComicDB can not open any comic books. To read your comics, you will need a comic reader on your local device. 

#### There are docker-compose files to enable authentication for the apache2 server. Use these at your own risk if you want to use ComicDB outside of your private network and do not forget to use a strong password.

# Getting started
- Install `docker` and `docker-compose`
- Copy or link your comic directories to src/comics
- run `fix_permissions.sh`
- modify `COMICDB_PATH` inside `ComicDB.sh` to match your installation directory 
- modify `COMPOSE_FILE` inside `ComicDB.sh` to
    - amd64.yaml (AMD64 and no authentication)
    - amd64_auth.yaml (AMD64 and authentication)
    - armhf.yaml (ARM and no authentication)
    - armhy_auth.yaml (ARM and authentification)
- change `AUTH_PASSWORD` inside your `.yaml` file to a strong password if you are using authentication
    - (optional) change `AUTH_USERNAME` too
- get an API key for the Comicvine API from [https://comicvine.gamespot.com/api/](https://comicvine.gamespot.com/api/) and put it into `api-key.ini`

## Server Control
- start the server by running `ComicDB.sh` or `ComicDB.sh start`
- to stop the server just run `ComicDB.sh stop`
- to stop the server and delete the docker containers, run `ComicDB.sh delete`. This will delete your database!
- to rebuild the images and start the server, run `ComicDB.sh build`. This will delete your database!

## Server Autostart
If you want to automatically start ComicDB when starting your computer, you can e.g. create a cron job that runs ComicDB.sh.

## Comics Organization
To get familiar with the way ComicDB expects you to organize your comics, please take a look at the included testing 
data and the Comicvine wiki.

# Testing Data
For testing purposes, ComicDB includes empty dummy files that will match to some comics.
You can use these to test if your connection to the API works as intended.
To prevent these dummies from showing up in your real library after testing, you should delete them 
from the comics directory and use the `Delete Database` button from the main menu to get a clean database for 
your production data. 

# Screenshots

<table>
<tr>
<td>
<img src="https://blog.ahahn94.de/wp-content/uploads/2018/10/comicdb_first_start.png"></td>
</tr>
<tr>
<td><img src="https://blog.ahahn94.de/wp-content/uploads/2018/10/comicdb_library.png"></td>
</tr>
<tr>
<td><img src="https://blog.ahahn94.de/wp-content/uploads/2018/10/comicdb_volume.png"></td>
</tr>
</table>

# Additional Information

## Comicvine API
ComicDB uses the [Comicvine API](https://comicvine.gamespot.com/api/).
Cover images and additional information on your comics will be downloaded from there.

## External Libraries
This project uses, among others, Bootstrap and FontAwesome.
As these libraries are only included by links to external servers, ComicDB will not work properly without an internet connection
(though this may change in a future release).

## Legacy Software
For compatibility reasons to ARMHF, this project uses MySQL 5.5.
This may change as newer versions become available as docker images for ARMHF.

## Copyright & License
Copyright (c) 2018 ahahn94.

ComicDB is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.