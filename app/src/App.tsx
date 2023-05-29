import useWindowSize from "@utils/hooks/useWindowSize";
import MainContent from "@components-app/MainContent/MainContent";
import React, { useEffect } from "react";
import DocFlowForm from "@components-app/DocFlowForm/DocflowForm";
import { CircularProgress } from "@material-ui/core";

/* import Navigation from "@components-app/Navigation/Navigation";
import NavigationMenu from "@components-app/NavigationMenu/NavigationMenu";
import ChromeReaderModeIcon from "@material-ui/icons/ChromeReaderMode";
import TableChartIcon from "@material-ui/icons/TableChart";
import { EntryType } from "@components-app/NavigationMenuItem/NavigationMenuItem";
import RightSideBar from "@components-app/RightSideBar/RightSideBar"; */

const App: React.FC = () => {
  //const [isOpen, setIsOpen] = React.useState(true);
  const [width, height] = useWindowSize();
  const [isLoaded, setIsLoaded] = React.useState(false);
  const [fileId, setFileId] = React.useState("");

  useEffect(() => {
    const queryParameters = new URLSearchParams(window.location.search);
    const fileIdParameterString = queryParameters.get("fileId");
    if (fileIdParameterString) {
      setFileId(fileIdParameterString);
    }
    setIsLoaded(true);
  }, []);
  if (isLoaded) {
    if(fileId) {
      return (
        <div id="app">
          {/* <Navigation isOpen={isOpen} setIsOpen={setIsOpen}>
              <NavigationMenu entries={entries} isOpen={isOpen} />
            </Navigation> */}
          <MainContent>
            <main
              id="main-content1"
            /* style={{
              transform: `translateX(${isOpen && width > 1023 ? 260 : 0}px)`,
              transition: "transform .35s ease-in-out",
            }} */
            >
              <DocFlowForm
                urlData = {window.location.protocol + "//" + window.location.host + OC.generateUrl("/apps/docflow/getdata")} 
                urlSubmit={window.location.protocol + "//" + window.location.host + OC.generateUrl("/apps/docflow/anonymize")}
                fileId={fileId}  
              >
              </DocFlowForm>
  
            </main>
          </MainContent>
          {/* <RightSideBar>Side Bar</RightSideBar> */}
        </div>
      );
    }
    return (
      <div id="app">
          {/* <Navigation isOpen={isOpen} setIsOpen={setIsOpen}>
              <NavigationMenu entries={entries} isOpen={isOpen} />
            </Navigation> */}
          <MainContent>
            <main
              id="main-content1"
            /* style={{
              transform: `translateX(${isOpen && width > 1023 ? 260 : 0}px)`,
              transition: "transform .35s ease-in-out",
            }} */
            >
             PUT USER GUIDE HERE
  
            </main>
          </MainContent>
          {/* <RightSideBar>Side Bar</RightSideBar> */}
        </div>
    );
    
  } else {
    return (
      <div id="app">
        <MainContent>
          <main
            id="main-content1"
          >
            <CircularProgress></CircularProgress>
          </main>
        </MainContent>
      </div>
    );
  }
};

export default App;

/* const entries: EntryType[] = [
  {
    id: "0",
    labelText: "Entry 0",
    labelIcon: TableChartIcon,
    color: "#000000",
    bgColor: "#69f0ae",
    children: [
      {
        id: "0-1",
        labelText: "Item 0-1",
        labelIcon: ChromeReaderModeIcon,
        action: () => console.log("item 0-1"),
      },
    ],
  },
  {
    id: "1",
    labelText: "Entry 1",
    labelIcon: TableChartIcon,
    color: "#000000",
    bgColor: "#69f0ae",
    children: [
      {
        id: "1-0",
        labelText: "Item 1-0",
        labelIcon: ChromeReaderModeIcon,
        action: () => console.log("item 1-0"),
      },
    ],
  },
  {
    id: "2",
    labelText: "Entry 2",
    labelIcon: TableChartIcon,
    color: "#000000",
    bgColor: "#69f0ae",
    children: [
      {
        id: "2-0",
        labelText: "Item 2-0",
        labelIcon: ChromeReaderModeIcon,
        action: () => console.log("item 2-0"),
      },
      {
        id: "2-1",
        labelText: "Item 2-1",
        labelIcon: ChromeReaderModeIcon,
        action: () => console.log("item 2-1"),
        children: [
          {
            id: "2-1-0",
            labelText: "Item 2-1-0",
            labelIcon: ChromeReaderModeIcon,
            action: () => console.log("item 2-1-0"),
          },
        ],
      },
    ],
  },
];
 */