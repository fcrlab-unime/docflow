import { Action, configureStore, ThunkAction } from '@reduxjs/toolkit';
import checkbox from './reducer/checkbox';
import checkboxList from './reducer/checkboxList';
//import reducer from '@store-settings/reducers/reducer

export const store = configureStore({
    reducer: {
        checkbox: checkbox,
        checkboxList: checkboxList
    }
});

export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;
export type AppThunk = ThunkAction<void,RootState,unknown,Action>;
