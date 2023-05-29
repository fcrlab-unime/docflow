import { hot } from "react-hot-ts";
import { store } from "@store-app/store";
import React from "react";
import ReactDOM from "react-dom";
import { Provider } from "react-redux";
import './index.css';
import App from "./App";

hot(module)(
  ReactDOM.render(
    <Provider store={store}>
      <App />
    </Provider>,
    document.getElementById("content")
  )
);
