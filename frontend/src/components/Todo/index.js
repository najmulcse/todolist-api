import React, { Component } from 'react';
import { getTodos, toggleCompleteStatus, createTodos } from '../../APIs/Todo.js';
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
            completedTodos : [],
            activeTab: 'all',
            total: 0,
            title: ''
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
                this.setState({totalLeft: this.getTotalIncompletedItems()})
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
            this.setState({totalLeft: this.getTotalIncompletedItems()});
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
           console.log(id);
           //todos.push(newItem);
           allTodos.push(newItem)
           this.setState({
               allTodos: allTodos,
               title: '',
               totalLeft: this.getTotalIncompletedItems()
           });
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
    
        this.setState({ todos: this.state.allTodos,  activeTab: 'complete',});
    }

    handleActiveList = () => {
        let allTodos = [...this.state.allTodos ];
        let activeTodos = allTodos.filter( todo => {
                            return todo.is_completed != 1;
                         });
        this.setState({ todos: activeTodos,  activeTab: 'active',});

    }
    handleCompletedList = () => {
        let allTodos = [...this.state.allTodos ];
        let completedTodos = allTodos.filter( todo => {
                            return todo.is_completed == 1;
                         });
        this.setState({ todos: completedTodos, activeTab: 'complete',});
    } 
    getTotalIncompletedItems()
    {
        var totalIncompleteItems = this.state.allTodos.filter( todo => {
            return todo.is_completed != 1;
             });
        return totalIncompleteItems.length;
    }

    render(){
        let { todos, isLoading, allTodos } = this.state;
        let listCount = allTodos.length;
        return <React.Fragment>
               
                <div className="container">
                <div className="col col-sm-8 col-md-8 .offset-sm-2 offset-md-2">
                    <h1 className="text-center pt-4">Todos</h1>
                
                        <div className="card">
                         
                        <input type="text" 
                            placeholder="What needs to be done?" 
                            className="pb-3 pt-3 pl-3" 
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
                                     <span className="pl-2" style={{ fontSize : '20px'}}>
                                         { todo.is_completed  == 1 ? 
                                         <strike>{ todo.title } </strike>
                                         : todo.title} 
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
                                <p> { this.state.totalLeft } item(s) left.</p>
                             
                                </div>
                                <div className="col-sm-8 col-md-8 col-lg-8">
                                    <button className="btn btn-primary" onClick={this.handleShowAllList} type="button" className="pl-2 pr-2 m-2"> All </button>
                                    <button onClick={this.handleActiveList } type="button" className="pl-2 pr-2 m-2"> Active </button>
                                    <button onClick={this.handleCompletedList} type="button" className="pl-2 pr-2 m-2"> Completed </button>
                                
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