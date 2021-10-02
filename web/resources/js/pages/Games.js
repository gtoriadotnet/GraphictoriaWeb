// © XlXi 2021
// Graphictoria 5

import axios from 'axios';
import React from "react";
import { Link, useHistory } from "react-router-dom";

import Config from '../config.js';

import SetTitle from "../Helpers/Title.js";

import { GenericErrorModal } from './Errors.js';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

class Games extends React.Component {
	constructor(props) {
		super(props);
		this.state = {offline: false};
	}
	
	componentDidMount()
	{
		var app = this;
		
		SetTitle("Games");
		
		function updateBanners()
		{
			axios.get(protocol + 'api.' + url + '/web/games/status').then((response) => {
				app.setState({offline: !response.data.available});
			});
		}
		
		updateBanners();
	}
	
	render()
	{
		return (
			this.state.offline
			?
			<GenericErrorModal title="Games Offline">
				<img src="/images/symbols/warning.png" width="100" className="mb-3" />
				<br />
				Seems like XlXi tripped over the game server's power cord again. Games are temporarily unavailable and administrators have been notified of the issue. Sorry for the inconvenience!
			</GenericErrorModal>
			:
			<></>
		);
	}
}

export { Games };