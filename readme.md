An autosaving daily form for up to 3 tasks a day with notes field. The form clears out every day. With a SQLite DB, so no other dependencies are required.

To add

- [ ] Will add some way to refer to previous day's forms.
- [ ] Might need a search if you're disciplined
- [ ] frankenphp this

# Setup

```shell
devbox shell #or bring your own PHP
composer install
```
Then you can run `composer run serve` to load up the local server

## Media

Screenshot:

![image](https://github.com/svandragt/daily-tasks/assets/594871/8b8aebf7-d0fb-41b8-9e74-63a65eecc5f2)

Printout version:

![Actual printout](https://user-images.githubusercontent.com/594871/186113649-ece82e1d-72f2-4533-b8e5-37c5fd5a3c8a.jpg)


## Home Server Setup

(This assumes a snap installed Docker (replaces `docker.service` with `snap.docker.dockerd.service`, and `/usr/bin` with `/snap/bin`).)

Build the image by running `docker build -t three-things .`

Run the container on startup:

```systemd
[Unit]
Description=Three Things
After=snap.docker.dockerd.service
Requires=snap.docker.dockerd.service

[Service]
Restart=always
ExecStart=/snap/bin/docker run --name my-three-things -p 9082:9082 three-things:latest
ExecStop=/snap/bin/docker stop my-three-things

[Install]
WantedBy=multi-user.target
```

_Note: Using the `:latest` tag in Docker can be a convenient way to ensure that your container always runs the latest version of the image. However, it's important to note that relying solely on the `:latest` tag for automatic updates may not always be the best practice, as it can lead to unexpected behavior if the image is updated in a way that breaks compatibility with your application._
