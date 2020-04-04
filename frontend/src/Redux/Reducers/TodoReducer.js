import { TodoActionType } from '../Actions/TodoActions';

const unPersistedState = {
    todoLists: [],
    
}

const defaultAuthState = {
    ...unPersistedState,
    lastItem: [],
   
}


const TodoReducer = (state = { ...defaultAuthState }, action) => {
    switch (action.type) {
        case TodoActionType.LAST_TODO_ITEM:
            return {
                ...state,
                todo: action.todo
            };

          
        default:
            return { ...state };
    }
}

export default TodoReducer;