import axios  from 'axios';
import { TODO_APIS, RESPONSE_STATUSES, HEADERS} from '../Config/APIs';


export const getTodos = () => {

    return axios.get(TODO_APIS.FETCH,{
            headers: HEADERS, 
    });

}

export const toggleCompleteStatus = (id, is_completed) => {
    return axios.put(TODO_APIS.TOGGLE_STATUS, 
        {
            id: id,
            is_completed : is_completed || 0,
        }
    )
}

export const createTodos = (title) => {
    return axios.post(TODO_APIS.CREATE, {
        title: title,
    });
}