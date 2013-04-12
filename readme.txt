You2MP3 is PHP web-based script that are able to convert any youtube video into MP3.
This script use external tool ffmpeg to convert video into MP3.

How to use ?

1. Download all file or unzip using zip package.
2. Find suitable ffmpeg for your host operating system. You can find it here -> http://ffmpeg.org/download.html
3. Put it into "tool" folder.
4. Edit options.php and fill up ffmpeg variable with your ffmpeg location. (Example : "tool\ffmpeg")
5. After that, fill up $admin_username and $admin_password with your username & password. ($admin_password in MD5 hash)
6. Enter admin.php and login with your admin credential, set up your settings and you are ready to go.
7. Enjoy ! :)

You can also change quality of video/mp3 file, just change $quality with low, medium or high. Default is medium.

Last but not least, this script is license with GNU General Public License.

v0.1 (Beta)
- Initial Release

v0.1.1 (Beta)
- Add logging
- Use .htaccess rather than empty index.php for forbidden indexing
- Add some useful function
- Get proper youtube link if user put youtube shortcut link

v0.1.2 (Beta)
- Add Admin Panel (admin.php)
- Add edit option inside admin panel
- Add log viewer inside admin panel
- Place .htaccess in main folder to block user access to log file

v0.1.3 (Beta)
- Add change login in admin panel
- Add log & log with audio deleter
- Change style

v0.1.4 (Beta)
- Use Curl Multithread when downloading video from youtube

v0.1.5 (Beta)
- Add debug mode in options.
- Add first_run.php for first user fill username & password form.
- Completing unfinish comment in function.
- Set output buffering on in admin.php file (cookie problem).
- Add logout button in admin.php
- Fix some bug.
- Lot of improvement.


Mohd Shahril @ 2013