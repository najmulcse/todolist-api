const BASE_URL = "http://localhost:8090/api";

const VERSION = "/v1";
const TODO = '/todo';

const BASE_API_URL =  BASE_URL + VERSION + TODO;


export const RequestMethods = {
    GET: 'get',
    POST: 'post',
    PUT: 'put',
    DELETE: 'delete'
}

export const RESPONSE_STATUSES = {
    SUCCESS: 'success',
    FAILED: 'failed'
}
export const RESPONSE_STATUSE_CODE = {
    SUCCESS: 200,
    FAILED: 500
}

export const HEADERS = {
    Accept: 'application/json',
    'Access-Control-Allow-Origin': '*',
}

export const MULTIPART_HEADERS = {
    Accept: 'application/json',
    'Content-Type': 'multipart/form-data',
}

export const TODO_APIS = {
    CREATE: BASE_API_URL + '/create',
    UPDATE: BASE_API_URL + '/update',
    DELETE: BASE_API_URL + '/delete',
    FETCH: BASE_API_URL + '/fetch',
    TOGGLE_STATUS: BASE_API_URL + '/toggleStatus',
}
