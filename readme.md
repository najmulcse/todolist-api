## Introduction

This is TodoList single page application. For frontend development i used ReactJs(javascript framework) and for backend API developement used PHP. Here database is used MySQL.  

## Installation process

* At first clone this project. Open a command prompt and type: git clone https://github.com/najmulcse/todolist-api.git

# Backend to connect API:
Please follow the process to run backend.
1. Create a databse (mySQL).
2. Now Goto backend folder. 
3. Open .env file, put your database name.( Example: DB_DATABASE=todo_api)  
4. Now open a command prompt in backend folder.
5. type 'php -S localhost:8090' and hit enter.

Note: Here 8090 port i used but you can change port as you want. If you change this port then you have to change API url in frontend part as well. 
FileLocation: frontend/src/Config/APIs.js . 
## Frontend(React) to display todo list.
Please follow the given instruction to run react project.
1. Goto frontend folder 
2. Open a command prompt in this folder.
3. type 'yarn install' and hit enter. (make sure you have installed yarn in your computer. It similar as npm(Node package manager))
4. then type 'yarn start'
5. You will see a todo list application is appeared. 

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
