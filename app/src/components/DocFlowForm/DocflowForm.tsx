import Checkbox from "@components-app/Checkbox/Checkbox";
import CheckboxList from "@components-app/CheckboxList/CheckboxList";
import { CircularProgress } from "@material-ui/core";
import { RootState } from "@store-app/store";
import React, { useEffect } from "react";
import { useSelector } from "react-redux";
import './checkbox.css';


interface FormProps {
  urlData: string;
  urlSubmit: string;
  fileId: string;
}
interface SensitiveData {
  sensitive_data_id: number;
  data: string;
  exclusivity_s: string;
  methods: Array<{
      methods_id: number;
      desc: string;
      exclusivity_m: string;
  }>;
}

const DocFlowForm: React.FC<FormProps> = ({ urlData, urlSubmit, fileId }) => {
  const [data, setData] = React.useState([]);
  const [isLoaded, setIsLoaded] = React.useState(false);
  const [isSubmitButtonDisabled, setIsSubmitButtonDisabled] = React.useState(true);

  const outerCheckboxState = useSelector((state: RootState) => state.checkbox.value)
  const innerCheckboxState = useSelector((state: RootState) => state.checkboxList.value)

  const onSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    //console.log("outer checkbox", outerCheckboxState);
    //console.log("inner checkbox", innerCheckboxState);

    const dataCopy: SensitiveData[] = data;
    const selectedData: { [key: number]: string } = {};
   
    Object.entries(outerCheckboxState).forEach(([key, value]) => {
      if (value) {
        const item = dataCopy.find(d => d.sensitive_data_id === parseInt(key));
        if (item) {
          selectedData[item.sensitive_data_id] = item.data;
        }
      }
    });

    //console.log(selectedData);

    if (window.confirm(`\nDati da anonimizzare: \n${Object.values(selectedData).join("\n")}\n\nVuoi confermare?`)) {

      let toSubmit = {
        "elem": {
          //"file": "",
          "file": fileId,
          "sensitiveData": []
        }
      };
      for (const outerCheckBoxKey in outerCheckboxState) {
        if (outerCheckboxState[outerCheckBoxKey]) {
          let sensitiveData = {
            "sensitiveDataId": parseInt(outerCheckBoxKey),
            "methods": []
          }
          for (const innerCheckBoxKey in innerCheckboxState[outerCheckBoxKey]) {
            if (innerCheckboxState[outerCheckBoxKey][innerCheckBoxKey]["value"]) {
              sensitiveData["methods"].push(parseInt(innerCheckBoxKey) as never);
            }
          }
          if (sensitiveData["methods"].length > 0) {
            toSubmit["elem"]["sensitiveData"].push(sensitiveData as never);
          }
        }
      }
      //console.log("to submit", toSubmit);

      const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(toSubmit),
      };

      let response = await fetch(urlSubmit, requestOptions) as unknown as Response;
      //console.log('response.status', response.status);
      switch (response.status) {
        case 200:
          //console.log('OK');
          let responseText = await response.text();
          //console.log('responseText', responseText);
          let responseJson = JSON.parse(responseText);
          console.log('responseJson', responseJson);
          /* let pdfViewUrl = OC.generateUrl('/apps/files/?dir=' + responseJson["dir"] + '&openfile=' + responseJson["id"]);
          window.location.replace(pdfViewUrl); */
          //savePdf(responseText);
          break;
        case 400:
          throw new Error('Bad Request');
        case 500:
          throw new Error('Internal Server Error');
        default:
          throw new Error('Unknown Error');
      }
      //console.log("Submitted");
    }
    else {
      //DO NOTHING
    }
  }

  useEffect(() => {
    const fetchData = async () => {
      const fetchedData = await (
        await fetch(urlData)
      ).json();
      setData(fetchedData);
    };
    const fetchAll = async () => {
      await fetchData();
      setIsLoaded(true);
    }
    fetchAll();
  }, []);

  useEffect(() => {
    //console.log('innerCheckboxState', innerCheckboxState);
    //console.log('outerCheckboxState', outerCheckboxState);
    let isButtonDisabled = true;
    for (const outerCheckBoxKey in outerCheckboxState) {
      if (outerCheckboxState[outerCheckBoxKey]) {
        for (const innerCheckBoxKey in innerCheckboxState[outerCheckBoxKey]) {
          if (innerCheckboxState[outerCheckBoxKey][innerCheckBoxKey]["value"]) {
            isButtonDisabled = false;
          }
        }
      }
    }
    setIsSubmitButtonDisabled(isButtonDisabled);
  }, [innerCheckboxState, outerCheckboxState]);

  //DEBUG LOGS START
  /* useEffect(() => {
    console.log('data', data)
  }, [data]);
  useEffect(() => {
    console.log('isLoaded', isLoaded)
  }, [isLoaded]);
  useEffect(() => {
    console.log('isSubmitButtonDisabled', isSubmitButtonDisabled)
  }, [isSubmitButtonDisabled]);
  useEffect(() => {
    console.log('outerCheckboxState', outerCheckboxState);
  }, [outerCheckboxState]);
  useEffect(() => {
    console.log('innerCheckboxState', innerCheckboxState);
  }, [innerCheckboxState]); */
  //DEBUG LOGS END

  if (isLoaded) {
    return (
      <div className="form-container">
        <form onSubmit={onSubmit}>
          <div className="checkList">
            <div className="title">Scegli come anonimizzare il documento:</div>
            <div className="list-container">
              {data.map((item: Object) => (
                <div
                  key={item["sensitive_data_id"] + " externalDiv"}
                  className="check-container">
                  <Checkbox
                    key={item["sensitive_data_id"] + " checkboxElement"}
                    input={{
                      type: "OBJECT",
                      data: {
                        id: item["sensitive_data_id"],
                        value: item["data"],
                        exclusivity: item["exclusivity_s"],
                      },
                    }}>
                    {outerCheckboxState[item["sensitive_data_id"]] ? (
                      <CheckboxList
                        key={item["sensitive_data_id"] + " checkboxListElement"}
                        id={item["sensitive_data_id"]}
                        input={
                          {
                            type: "ARRAY",
                            source: item["methods"],
                            data: {
                              id: "methods_id",
                              value: "desc",
                              exclusivity: "exclusivity_m",
                              default: "default",
                            },
                          }
                        }>
                      </CheckboxList>
                    ) : null}
                  </Checkbox>
                </div>
              ))}
            </div>
          </div>
          <button
            type="submit"
            disabled={isSubmitButtonDisabled}>
            Conferma
          </button>
        </form>
      </div>
    );
  } else {
    return (
      <CircularProgress></CircularProgress>
    );
  }
}

export default DocFlowForm;
