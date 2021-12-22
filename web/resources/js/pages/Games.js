// © XlXi 2021
// Graphictoria 5

import axios from 'axios';
import React, { useState } from "react";
import { Link, useHistory } from "react-router-dom";

import Config from '../config.js';

import SetTitle from "../Helpers/Title.js";

import Loader from '../Components/Loader.js';

import { GenericErrorModal } from './Errors.js';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

class Games extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			offline: false,
			loading: true
		};
	}
	
	componentDidMount()
	{
		var app = this;
		
		SetTitle('Games');

		axios.get(protocol + 'apis.' + url + '/games/metadata')
			.then((response) => {
				app.setState({loading: !(response.data.available == false), offline: !response.data.available});
			});
	}
	
	render()
	{	
		return (
			this.state.loading
			?
			<Loader />
			:
			(
				this.state.offline
				?
				<GenericErrorModal title="Games Offline">
					<img src="/images/symbols/warning.png" width="100" className="mb-3" />
					<br />
					Seems like XlXi tripped over the game server's power cord again. Games are temporarily unavailable and administrators have been notified of the issue. Sorry for the inconvenience!
				</GenericErrorModal>
				:
				<></>
			)
		);
	}
}

export { Games };