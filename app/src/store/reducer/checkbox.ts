import { PayloadAction, createSlice } from "@reduxjs/toolkit";

type checkBoxPayload = {
    id: string;
    value: boolean;
}
type exclusivityPayload = {
    id: string;
    value: string;
}
const checkboxSlice = createSlice({
    name: "checkbox",
    initialState: {
        value: {},
        exclusivity: []
    },
    reducers: {
        exSet: (state, action: PayloadAction<exclusivityPayload>) => {
            state.exclusivity[action.payload.id] = action.payload.value;
        },
        update: (state, action: PayloadAction<checkBoxPayload>) => {
            state.value[action.payload.id] = action.payload.value;
        }
    }
});

export const { update, exSet } = checkboxSlice.actions;
export default checkboxSlice.reducer;