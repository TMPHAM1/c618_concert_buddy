import types from '../actions/types';

const DEFAULT_STATE = {
    concert: {}
};

export default function (state = DEFAULT_STATE, action) {
    switch (action.type) {
        case types.GET_CONCERT_DETAILS:
            return {
                ...state, concert: action.payload.data._embedded.events[0]
            }
        default:
            return state;
    }
}