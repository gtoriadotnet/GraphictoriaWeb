// © XlXi 2021
// Graphictoria 5

import React from "react";
import { Link, useHistory } from "react-router-dom";

import SetTitle from "../Helpers/Title.js";

import { GenericErrorModal } from './Errors.js';

class Games extends React.Component {
	componentDidMount()
	{
		SetTitle("Games");
	}
	
	render()
	{
		return (
			<GenericErrorModal title="Games Offline">
				<img src="/images/symbols/warning.png" width="100" className="mb-3" />
				<br />
				Seems like XlXi tripped over the game server's power cord again. Games are temporarily unavailable and administrators have been notified of the issue. Sorry for the inconvenience!
			</GenericErrorModal>
		);
	}
}

export { Games };