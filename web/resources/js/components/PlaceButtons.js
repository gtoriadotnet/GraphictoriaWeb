/*
	Copyright © XlXi 2022
*/

import { Component, createRef } from 'react';
import axios from 'axios';
import Twemoji from 'react-twemoji';

import classNames from 'classnames/bind';

import { buildGenericApiUrl, getCurrentDomain } from '../util/HTTP.js';
import Loader from './Loader';

axios.defaults.withCredentials = true;

const playerProtocol = 'virtubrick-player';

class PlaceLoadingModal extends Component {
	constructor(props) {
		super(props);
		this.state = {
			showDownloadScreen: false
		};
		
		this.ModalRef = createRef();
		this.Modal = null;
	}
	
	componentDidMount() {
		this.Modal = new Bootstrap.Modal(this.ModalRef.current);
		this.Modal.show();
		
		let gone = false;
		this.ModalRef.current.addEventListener('hidden.bs.modal', (event) => {
			gone = true;
			this.props.setModal(null);
		});
		
		setTimeout(function(){
			this.setState({showDownloadScreen: true});
		}.bind(this), 10000);
		
		setTimeout(function(){
			if(gone == false)
				this.props.setModal(null);
		}.bind(this), 20000);
	}
	
	componentWillUnmount() {
		this.Modal.dispose();
	}
	
	render() {
		return (
			<div ref={this.ModalRef} className="modal fade">
				<div className="modal-dialog modal-dialog-centered">
					<div className={classNames({
						'modal-content': true,
						'text-center': true,
						'mx-5': (!this.state.showDownloadScreen)
					})}>
						<div className={classNames({
							'modal-body': true,
							'd-flex': true,
							'flex-column': true,
							'pb-5': (!this.state.showDownloadScreen)
						})}>
							<button type="button" className="ms-auto btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							{
								this.state.showDownloadScreen ?
								<>
									<h5>Download VirtuBrick</h5>
									<p>Download VirtuBrick to get access to thousands of community-driven games.</p>
									<a href={ buildGenericApiUrl('setup', 'VirtuBrickPlayerLauncher.exe') } target="_blank" className="btn btn-success mt-3">Download</a>
								</>
								:
								<>
									<Loader />
									<p>Starting VirtuBrick</p>
								</>
							}
						</div>
					</div>
				</div>
			</div>
		);
	}
}

class PlaceLoadingErrorModal extends Component {
	constructor(props) {
		super(props);
		this.state = {
		};
		
		this.ModalRef = createRef();
		this.Modal = null;
	}
	
	componentDidMount() {
		this.Modal = new Bootstrap.Modal(this.ModalRef.current);
		this.Modal.show();
		
		this.ModalRef.current.addEventListener('hidden.bs.modal', (event) => {
			this.props.setModal(null);
		});
	}
	
	componentWillUnmount() {
		this.Modal.dispose();
	}
	
	render() {
		return (
			<div ref={this.ModalRef} className="modal fade">
				<div className="modal-dialog modal-dialog-centered">
					<div className="modal-content text-center">
						<div className="modal-body d-flex flex-column pb-4">
							<button type="button" className="ms-auto btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							<h5>An error occurred while starting VirtuBrick.</h5>
							<p>Error Detail: { this.props.message }</p>
						</div>
					</div>
				</div>
			</div>
		);
	}
}

class PlaceButtons extends Component {
	constructor(props) {
		super(props);
		this.state = {
			playDebounce: false,
			showModal: false
		};
		
		this.playGame = this.playGame.bind(this);
		this.setModal = this.setModal.bind(this);
	}
	
	componentDidMount() {
		let placeElement = this.props.element;
		if (placeElement) {
			this.placeId = parseInt(placeElement.getAttribute('data-place-id'))
		}
	}
	
	setModal(modal = null) {
		this.visibleModal = modal;
		
		if(modal) {
			this.setState({'showModal': true});
		} else {
			this.setState({'showModal': false});
		}
	}
	
	playGame() {
		this.setState({ playDebounce: true });
		setTimeout(function(){
			this.setState({ playDebounce: false });
		}.bind(this), 1000);
		
		this.setModal(<PlaceLoadingModal setModal={ this.setModal } />);
		
		let protocol = playerProtocol;
		let domainSplit = getCurrentDomain().split('.');
		if(getCurrentDomain() == 'virtubrick.local')
		{
			protocol += '-dev';
		}
		else if(domainSplit.length > 2)
		{
			protocol += '-' + domainSplit.at(-3); // XlXi: Third to last
		}
		
		axios.get(buildGenericApiUrl('api', `auth/v1/generate-token`))
			.then(res => {
				window.location = protocol
								+ ':1'
								+ '+launchmode:play'
								+ '+gameinfo:' + res.data
								+ '+placelauncherurl:' + encodeURIComponent(buildGenericApiUrl('www', `Game/PlaceLauncher?request=RequestGame&placeId=${this.placeId}&isPlayTogetherGame=false`));
			})
			.catch(function(error) {
				this.setModal(<PlaceLoadingErrorModal setModal={ this.setModal } message={ error.message } />);
				
				//alert('Error while starting VirtuBrick: ' + error.message);
			}.bind(this));
	}
	
	render() {
		return (
			<>
				<button className="btn btn-lg btn-success fs-3" onClick={ this.playGame } disabled={ this.state.playDebounce }>
					<i className="fa-solid fa-play"></i>
				</button>
				{ this.state.showModal ? this.visibleModal : null }
			</>
		);
	}
}

export default PlaceButtons;