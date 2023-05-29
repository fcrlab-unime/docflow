import { exSet, update } from "@store-app/reducer/checkbox";
import { RootState } from "@store-app/store";
import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";

interface Data {
    id: string;
    value: string;
    exclusivity: string;
}

interface CheckboxInputURL {
    type: "URL";
    source: string;
    data: Data;
}
interface CheckboxInputObject {
    type: "OBJECT";
    data: Data;
}

type CheckboxInput = CheckboxInputURL | CheckboxInputObject;

interface CheckboxProps {
    input: CheckboxInput;
}

const Checkbox: React.FC<CheckboxProps> = ({ input, children }) => {
    const [data, setData] = useState<Data>({ id: "", value: "", exclusivity: "" });
    const checkboxState = useSelector((state: RootState) => state.checkbox.value);
    const checkboxExclusivity = useSelector((state: RootState) => state.checkbox.exclusivity);
    const dispatch = useDispatch();

    useEffect(() => {
        // fetch data
        switch (input.type) {

            case "URL":
                const dataFetch = async () => {
                    const fetchedData = await (
                        await fetch(
                            input.source
                        )
                    ).json();
                    let data: Data = {
                        id: fetchedData[input.data.id],
                        value: fetchedData[input.data.value],
                        exclusivity: fetchedData[input.data.exclusivity]
                    };
                    if (!(data.id in checkboxState)) {
                        dispatch(update(
                            {
                                value: false,
                                id: data.id
                            }
                        ))
                    }
                    if (!(data.id in checkboxExclusivity)) {
                        dispatch(exSet(
                            {
                                value: data.exclusivity,
                                id: data.id
                            }
                        )
                        )
                    }

                    // set state when the data received
                    setData(data);
                };

                dataFetch();
                break;

            case "OBJECT":
                const data = input.data;
                if (!(data.id in checkboxState)) {
                    dispatch(update(
                        {
                            value: false,
                            id: data.id
                        }
                    ))
                }
                if (!(data.id in checkboxExclusivity)) {
                    dispatch(exSet(
                        {
                            value: data.exclusivity,
                            id: data.id
                        }
                    ))
                }
                // set state when the data received
                setData(data);
                break;
            default:
                break;
        }
    }, []);

    //DEBUG LOGS START
    /* useEffect(() => {
        console.log('data', data)
    }, [data]); */
    //DEBUG LOGS END



    const handleOnChange = (id) => {
        if (!checkboxState[id]) {
            checkboxExclusivity.forEach((exclusivityValue, exclusivityId) => {
                if (exclusivityId != id && exclusivityValue != checkboxExclusivity[id]) {
                    dispatch(update(
                        {
                            value: false,
                            id: exclusivityId as unknown as string
                        }
                    ))
                }
            })

        }
        dispatch(update(
            {
                value: !checkboxState[id],
                id: id
            }
        ))
    };

    return (
        <div key={data.value + "-" + Math.random().toString(36).substring(2, 7)}>
            <input
                type="checkbox"
                id={data.value + "-" + Math.random().toString(36).substring(2, 7)}
                name={data.value}
                value={data.id}
                checked={checkboxState[data.id] ? checkboxState[data.id] : false}
                onChange={() => handleOnChange(data.id)}
            />
            <label>{data.value}</label>
            {children}
        </div>

    );
};

export default Checkbox;