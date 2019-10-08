![PHPGit logo](https://i.imgur.com/fg3qMGn.png)
PHPGit is a web based terminal that can be used to push from your hosting server to your Github repo. PHPGit is like a clone of GIT desktop app. So most of the commands used for PHPGit is similar to GIT commands. PHPGit also partially supports `.gitignore` files.

# Installation
To install PHPGit, Just clone or download the zip of this repo and put it in your server. PHPGit doesn't have any dependency, It will work on `PHP 5.xx >`
Then run the `index.php` file.

# Commands
## git init
Used to point to the folder in the server which have to be uploaded to Github
`````
git init [DIR PATH]
`````
*Examples:* 
`````
git init helloworld
git init ../abc/helloworld
`````
## git repo
Used to set the repository url of the github repo. The files from the server will be pushed to this repository. Give the github repository clone link `(eg: https://github.com/Niyko/PHPGit.git)`
`````
git repo [REPO URL]
`````
*Examples:* 
`````
git repo https://github.com/Niyko/PHPGit.git
`````
## git commit
Used to set the commit changes message, Same as github commit changes text
`````
git commit [MSG]
`````
*Examples:* 
`````
git commit first commit
git commit something changed
`````
## git key
Used to set the github personal access token to authenticate the user with github. You can create one from https://github.com/settings/tokens 
For more help to create a key, read this https://help.github.com/en/articles/creating-a-personal-access-token-for-the-command-line
`````
git key [PERSONAL ACCESS TOKEN]
`````
*Examples:* 
`````
git key 3j3484j45843u4574y3e6etwq5wq7fru7
`````
## git push
Used to push the files from server to the server to the github repo
`````
git push
`````
*Examples:* 
`````
git push
`````
## git clear
Used to clear the terminal screen
`````
git clear
`````
*Examples:* 
`````
git clear
`````
## git view
Used to print all currently set variables like `Repo link, Personal access key, etc`
`````
git view
`````
*Examples:* 
`````
git view
`````
## git pass
Used to authenticate you to the server if you set any password in the `config.php` file. 
> Note that this is not your github password, This password is used to authenticaye between the admin (YOU) and the (YOUR) server. You can set the password in the `config.php` file. For more read below.
`````
git pass [PASSWORD]
`````
*Examples:* 
`````
git pass abcd
`````
# Config.php
Config.php has some global variables which can be used to set some default values to the `Repo link, Personal access key, etc`. It is not necessary to edit this files. Uses of each variable in the `config.php` is given below

| Variable | Use | Example value |
| --- | --- | --- |
| `$DEFUALT_GITHUB_REPO` | Used to set a default value to repo url. Can be used from the command `git repo default` | https://github.com/Niyko/PHPGit.git |
| `$DEFUALT_GITHUB_AUTH_KEY` | Used to set a default value to personal access token. Can be used from the command `git key default` | 87dsys]8cd87cd6t326t23r78 |
| `$DEFUALT_GITHUB_COMMIT_MESSAGE` | Used to set a default value to the commit message. Can be used from the command `git commit default` | First commit |
| `$DEFUALT_PASSWORD` | Used to set a password to the terminal. If you set a password string in this variable, Then you can push or commit from the terminal after you authenticated through the command `git pass [PASSWORD]` | abcd |

# License
PHPGit is licensed under the [GNU GENERAL PUBLIC LICENSE](https://github.com/Niyko/PHPGit/blob/master/LICENSE).
