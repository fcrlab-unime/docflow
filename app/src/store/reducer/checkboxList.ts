import { PayloadAction, createSlice } from "@reduxjs/toolkit";

type checkBoxListSetPayload = {
    id: string;
    values: Object;
}

type checkBoxListUpdatePayload = {
    idList: string;
    idCheckBox: string;
    value: Object;
}

const checkBoxListSlice = createSlice({
    name: "checkBoxList",
    initialState: {
        value: {}
    },
    reducers: {
        setList: (state, action: PayloadAction<checkBoxListSetPayload>) => {
            state.value[action.payload.id] = action.payload.values;
        },
        update: (state, action: PayloadAction<checkBoxListUpdatePayload>) => {
            state.value[action.payload.idList][action.payload.idCheckBox] = action.payload.value;
        }
    }
});

export const { update, setList } = checkBoxListSlice.actions;
export default checkBoxListSlice.reducer;