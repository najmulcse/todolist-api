import React, { Component } from 'react';
import { getTodos, toggleCompleteStatus, createTodos, deleteTodo, updateTodo } from '../../APIs/Todo.js';
import { RESPONSE_STATUSE_CODE } from '../../Config/APIs';
import { Icons } from '../../Shared/Icons';
import './todo.css';


class  TodoPage extends Component{

    constructor(props)
    {
        super(props);
        this.state = {
            isLoading: true,
            todos: [],
            allTodos: [],
            countCompletedTodos : 0,
            activeTab: 'all',
            total: 0,
            title: '',
            titleUpdated:{
                isCompleted: 0,
                id: '',
                title: ''
            },
        }
    }
    componentDidMount(){
        this.getAllList();
    }
    

    getAllList(){
        getTodos().then(response => {
            if(response.data.status_code === RESPONSE_STATUSE_CODE.SUCCESS){
                let { todos } = response.data.data
                this.setState({ todos : todos, allTodos: todos,});
                this.setState({
                    totalLeft: this.getTotalIncompletedItems(),
                    countCompletedTodos: this.getAllCompletedTodo().length
                })
            }
            this.setState({isLoading: false,})
            
        
    }).catch(error => {
            console.log(error);
    });
    }
     handleOnComplete = (todo) => {
            let todosList = [ ...this.state.todos ];
            let index = todosList.indexOf(todo);
            let filterItem = todosList.filter( t => {
                        return t.id === todo.id
            });
            let filterTodo = filterItem[0];
                filterTodo.is_completed = filterTodo.is_completed == 1 ? 0 : 1 ;
           
            todosList[index] = filterTodo;
        
            let updatedStatus = filterTodo.is_completed;
            let requestedTodoId = filterTodo.id;

            
            this.setState({todos: todosList});
            this.setState({
                totalLeft: this.getTotalIncompletedItems(),
                countCompletedTodos: this.getAllCompletedTodo().length});
            // call api for updating status
            toggleCompleteStatus(requestedTodoId, updatedStatus)
            .then( response => {
                   // console.log(response);
            })
            .catch(error => {
                    console.log(error);
            });
            
    }
    handleCreateList = (event) =>  {
        if(event.key === 'Enter' && this.state.title !=''){
           
           let id, { todos, allTodos, title } = this.state;
           if(allTodos.length){
            let lastItem = allTodos.slice(-1);
             id = lastItem[0];
           }else{
               id = 1;
           }
          
           let newItem = {id: id, title: title, is_completed: 0};
         
           allTodos.push(newItem)
           this.renderSelectedTodo();
           
           // calling create api 
           createTodos(title)
                .then(response => {
                        console.log(response);
                })
                .catch(error => {

                });
            
        }
        
    }
    changeTitle = (e) => {
        this.setState({
            title: e.target.value
        });
    }
    handleShowAllList = () => {
    
        this.setState({ todos: this.state.allTodos,  activeTab: 'all',});
    }

    renderSelectedTodo(){
        let { todos, allTodos } = this.state;
        switch(this.state.activeTab){
            case 'active':
                todos = this.getAllActiveTodo();
                 break;
            default:
                todos =   allTodos;   
        }

        this.setState({
            todos: todos,
            title: '',
            totalLeft: this.getTotalIncompletedItems()
        });
    }
    handleActiveList = () => {
       let activeTodos = this.getAllActiveTodo();
        this.setState({ todos: activeTodos,  activeTab: 'active',});

    }
    handleCompletedList = () => {
        let completedTodos = this.getAllCompletedTodo();
        this.setState({ 
             todos: completedTodos, activeTab: 'complete',
             countCompletedTodos: completedTodos.length});
    } 
    handleCompletedClear = () => {
        let completedTodos = this.getAllCompletedTodo();
        let activeTodos = this.getAllActiveTodo();
        this.setState({
            todos: activeTodos, 
            allTodos: activeTodos,
            activeTab: 'all',
            countCompletedTodos: 0,
        });
        completedTodos.map( todo => {
            
            deleteTodo(todo.id)
                .then(response => {
                        console.log(response);
                }).catch(error => {
                    console.log(error);
                })
        });
    }
    renderTitleInput = () => {
        let { titleUpdated } = this.state;
        return (
            <React.Fragment>
                 <span  className="pl-2" style={{ fontSize : '20px'}}>                                
                    <input name="titleUpdate" onBlur={this.onCloseInput} onKeyPress={ this.handleUpdateTitle }   onChange={this.titleUpdate} defaultValue={ titleUpdated.title } />
                </span>
              
            </React.Fragment>
        );
    }
    onCloseInput = () => {
        this.setState({titleUpdated: {}});
    }

    handleUpdateTitle = (event) => {
        let { titleUpdated: { id, title, isCompleted}, todos } = this.state;

        if(event.key === 'Enter' && title !==''){
            
            let filterItem = todos.filter( t => {
                        return t.id === id
            });
            let filterTodo = filterItem[0];
                filterTodo.title = title ;
            let index = todos.indexOf(filterTodo);
        
            todos[index] = filterTodo;
           
            
            updateTodo(id,title, isCompleted )
            .then( response => {
                console.log(response);
            })
            .catch( error => {
                console.log(error);
            });
            this.setState({todos, titleUpdated: {}});
        }
    
        
    }

    titleUpdate = (event) =>{
        let { titleUpdated:{ id, isCompleted } } = this.state;
        this.setState({titleUpdated: {
            isCompleted: isCompleted,
            title: event.target.value,
            id: id
        }}); 
      
    }
    handleUpdateTodoTitle (todo) {

       this.setState({titleUpdated: {
           isCompleted: todo.is_completed,
           id: todo.id,
           title: todo.title
       }});
 
    }
    getAllCompletedTodo(){
        let allTodos = [...this.state.allTodos ];
        let completedTodos = allTodos.filter( todo => {
                            return todo.is_completed == 1;
                         });
         return completedTodos;                
    }
    getTotalIncompletedItems()
    {
        var totalIncompleteItems = this.state.allTodos.filter( todo => {
            return todo.is_completed != 1;
             });
        return totalIncompleteItems.length;
    }

    getAllActiveTodo(){
        let allTodos = [...this.state.allTodos ];
        let activeTodos = allTodos.filter( todo => {
                            return todo.is_completed != 1;
                         });

            return activeTodos;             
    }
    render(){
        let { todos, isLoading, allTodos, countCompletedTodos, activeTab, titleUpdated } = this.state;
        let listCount = allTodos.length;
        return <React.Fragment>
               
                <div className="container">
                <div className="col col-sm-8 col-md-8 .offset-sm-2 offset-md-2">
                    <h1 className="text-center pt-4 todo-header">Todos</h1>
                
                        <div className="card">
                         {!listCount ? '':
                         <img className="title-input-dropdown" src={Icons.DownArrow}/>
                         }
                        <input type="text" 
                            placeholder="What needs to be done?" 
                            className="pb-3 pt-3 todo-title-input" 
                            name="title" 
                            value={this.state.title || ''}
                            onChange={this.changeTitle}
                            onKeyPress={this.handleCreateList }/>   
                       
                         { !allTodos.length ? '':
                         <div className="p-3 m-2">
                         {
                         isLoading ?  <p>Loading...</p>: '' 
                            
                         }
                         <div >
                         <ul className="p-0 todo-ul">
                         {  todos.map((todo, i) => {
                     
                         return (
                             
                                 <li key={i}>  
                                 <img 
                                     className="todo-icon" 
                                     key={i} 
                                     onClick={ () => this.handleOnComplete(todo)
                                     }
                                     src={ todo.is_completed == 1 ? Icons.TicsMarks: Icons.Circle }/>
                                        <span style={{ display:'inline-flex'}}>
                                            <div>
                                                { titleUpdated.id == todo.id ?  this.renderTitleInput() :
                                                <span onClick={
                                                    () =>this.handleUpdateTodoTitle(todo)}  className="pl-2" style={{ fontSize : '20px'}}>
                                                
                                                { todo.is_completed  == 1 ? 
                                                <strike>{ todo.title } </strike>
                                                : todo.title} 
                                            </span>
                                            
                                                }
                                                
                                            </div>
                                            
                                         </span>
                                         <hr/>
                                 </li>

                         )
                         })}
                         </ul>
                         </div>
                     </div>
                         }
                        { !listCount ? '': 
                            <div className="container">
                                <div className="pl-3 row">
                                <div className="col-sm-4 col-md-4 col-lg-4">
                                { !this.state.totalLeft ? '': 
                                    <p> 
                                         { this.state.totalLeft } item{ this.state.totalLeft > 1 ? 's': '' } left
                                    </p>
                                }
                             
                                </div>
                                <div className="col-sm-8 col-md-8 col-lg-8">
                                    <span className="btn btn-primary" onClick={this.handleShowAllList} type="button" className={ 'pl-2 pr-2 pt-2 pb-1 m-2 ' + (activeTab == 'all' ? 'active': '')}> All </span>
                                    <span onClick={this.handleActiveList } type="button" className={ 'pl-2 pr-2 pt-2 pb-1 m-2 ' + (activeTab === 'active'? 'active': '')}> Active </span>
                                    <span onClick={this.handleCompletedList} type="button" className={ 'pl-2 pr-2 pt-2 pb-1 m-2 ' + (activeTab === 'complete' ? 'active': '')}> Completed </span>
                                    { !countCompletedTodos ? '':
                                        <span onClick={this.handleCompletedClear} type="button" className="pl-2 pr-2 pt-2 pb-1 m-2"> Clear completed </span>
                                    }
                                </div>
                            </div>
                            </div>
                        }
                        </div>
                    </div>
                </div>
            </React.Fragment>
    }
}

export default TodoPage;