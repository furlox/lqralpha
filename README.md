Depends
-------
nodejs-websocket readme for getting node.js and installing nodejs-websocket.

Install (get node installed first)
-------
```
cd /var/www/ (or /srv/http/ or htdocs/ or wherever)
git clone https://github.com/furlox/lqralpha
chmod -R 777 lqralpha/
cd lqralpha
npm install nodejs-websocket
```
Running
-------
```
node server.js <port>
``` 
Make sure that the same port is used in the WebSocket connection in index.php (```ws:// ...```).
Forward all incoming traffic for the server.js port and port 80 to your web host computer.
Set up sane permissions after (chmod 777 will work but is bad). User account running the HTTP 
server should be able to write to the files/folders being accessed. If newly created subrooms
don't seem to work, they are probably write-protected. Also check for the auth-bit messing up
things (<roomname>____auth.txt)

Commands
--------
Subrooms and invites are handled by Node, rest is PHP. Don't. Ask. Why. "rest" is polled every 500ms
through AJAX and whenever the hash for the chat changes, the file is loaded. Approx. ~1mb per user per hour.


Other
-----
- Renaming the .pngs in ```flags/``` should give full country names in the registration drop-down,
like Armenia.png instead of ```ar.png```. 
- You can embed links like this: ```{www.google.com}{Google}```, and images like: ```[[[url]]]```
- If the user does not disconnect gracefully (e.g. browser process is killed), the online user list might not remove their name.
To prevent this, schedule a cron to run every minute or so and clear out alive.txt
- Don't panic and carry a towel.
