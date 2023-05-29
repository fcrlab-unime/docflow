import { setList, update } from "@store-app/reducer/checkboxList";
import { RootState } from "@store-app/store";
import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";

interface Data {
    id: string;
    value: string;
    exclusivity: string;
    default: string;
}

interface CheckboxInputURL {
    type: "URL";
    source: string;
    data: Data;
}
interface CheckboxInputArray {
    type: "ARRAY";
    source: Array<Object>;
    data: Data;
}

type CheckboxInput = CheckboxInputURL | CheckboxInputArray;

interface CheckboxListProps {
    id: string;
    input: CheckboxInput;

}

const CheckboxList: React.FC<CheckboxListProps> = ({ id, input, children }) => {
    const checkboxListState = useSelector((state: RootState) => state.checkboxList.value);
    const dispatch = useDispatch();
    const [data, setData] = useState<Data[]>([]);

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
                    let data: Data[] = [];
                    let state = {};
                    fetchedData.forEach(element => {
                        let newData: Data = {
                            id: element[input.data.id],
                            value: element[input.data.value],
                            exclusivity: element[input.data.exclusivity],
                            default: element[input.data.default]
                        };
                        data.push(newData);
                        state[element[input.data.id]] = {
                            value: element[input.data.default],
                            exclusivity: element[input.data.exclusivity]
                        };
                    });

                    if (!(id in checkboxListState)) {
                        dispatch(setList(
                            {
                                id: id,
                                values: state,

                            }
                        ))
                    }

                    // set state when the data received
                    setData(data);
                };

                dataFetch();
                break;

            case "ARRAY":
                let data: Data[] = [];
                let state: Object = {};
                input.source.forEach(element => {
                    let newData: Data = {
                        id: element[input.data.id],
                        value: element[input.data.value],
                        exclusivity: element[input.data.exclusivity],
                        default: element[input.data.default]
                    };
                    data.push(newData);
                    state[element[input.data.id]] = {
                        value: element[input.data.default],
                        exclusivity: element[input.data.exclusivity]
                    };
                });
                if (!(id in checkboxListState)) {
                    dispatch(setList(
                        {
                            id: id,
                            values: state,

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
    }, [data]);
    useEffect(() => {
        console.log('checkboxListState', checkboxListState);
    }, [checkboxListState]); */
    //DEBUG LOGS END

    const handleOnChange = (event, checkboxId) => {
        //console.log('checkboxListState', checkboxListState)
        let newState = { ...checkboxListState[id][checkboxId] };
        if (!newState["value"]) {
            Object.keys(checkboxListState[id]).forEach((index) => {
                if (checkboxListState[id][index]["exclusivity"] != checkboxListState[id][checkboxId]["exclusivity"]) {
                    dispatch(update(
                        {
                            idList: id,
                            idCheckBox: index,
                            value: {
                                value: false,
                                exclusivity: checkboxListState[id][index]["exclusivity"]
                            },
                        }
                    ))
                }
            });
        }
        newState["value"] = !newState["value"];
        dispatch(update(
            {
                idList: id,
                idCheckBox: checkboxId,
                value: newState,
            }
        ))
    };

    return (
        <div className="checkList">
            {/* <div className="title">Your CheckList:</div> */}
            <div className="list-container">

                {data.map((item: Data, index) => (
                    <div key={item.value + " checkboxList"}>
                        <input
                            type="checkbox"
                            id={item.value}
                            name={item.value}
                            value={item.id}
                            checked={checkboxListState[id][item.id]["value"]}
                            onChange={event => handleOnChange(event, item.id)}
                        />
                        <label>{item.value}</label>
                        {children}
                    </div>
                ))}
            </div>
        </div>

    );
};

export default CheckboxList;