import axios  from 'axios';
import { TODO_APIS, RESPONSE_STATUSES, HEADERS} from '../Config/APIs';


export const getTodos = () => {

    return axios.get(TODO_APIS.FETCH);

}

export const toggleCompleteStatus = (id, is_completed) => {
    return axios.put(TODO_APIS.TOGGLE_STATUS, {
        headers: HEADERS,
        data:{
            id: id,
            is_completed : is_completed || 0,
        }
    });
}

export const createTodos = (title) => {
    return axios.post(TODO_APIS.CREATE, {
        headers: HEADERS,
        data:{
            title: title,
        }
    });
}

export const deleteTodo = (id) => {
    return axios.delete(TODO_APIS.DELETE,{
            data:{
                id: id
            }
        }
       );
}

export const updateTodo = (id, title, isCompleted) => {
    return axios.put(TODO_APIS.UPDATE,{
        headers: HEADERS,
        data: {
            id: id, 
            title: title,
            is_completed: isCompleted
        }
    })
}