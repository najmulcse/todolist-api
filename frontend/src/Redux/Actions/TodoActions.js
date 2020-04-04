export const TodoActionType = {
    LAST_TODO_ITEM: 'LAST_TODO_ITEM',
};

export const lastTodoItem = (todo) => {
    return {
        type: TodoActionType.LAST_TODO_ITEM,
        todo,
    };
}
