#!/bin/bash
sudo add-apt-repository "deb http://archive.ubuntu.com/ubuntu $(lsb_release -sc) main universe restricted multiverse"
sudo apt-get update
sudo apt-get install -y libxml2-dev libxslt-dev zlib1g-dev python2.7-dev
sudo pip install lxml
sudo pip install requests
chmod +x parser.py