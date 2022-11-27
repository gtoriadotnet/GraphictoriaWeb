/*
	Graphictoria 5 (https://gtoria.net)
	Copyright © XlXi 2022
*/

import { Component, createRef } from 'react';

import classNames from 'classnames/bind';

import axios from 'axios';

import { buildGenericApiUrl } from '../util/HTTP.js';
import Loader from './Loader';

axios.defaults.withCredentials = true;

class DeploymentUploadModal extends Component {
	constructor(props) {
		super(props);
		this.state = {
			deployments: []
		};
		
		this.updateInterval = null;
		
		this.ModalRef = createRef();
		this.Modal = null;
	}
	
	componentDidMount() {
		this.Modal = new Bootstrap.Modal(
			this.ModalRef.current,
			{
				backdrop: 'static',
				keyboard: false
			}
		);
		this.Modal.show();
		
		this.ModalRef.current.addEventListener('hidden.bs.modal', (event) => {
			this.props.setModal(null);
		})
		
		this.setState({ deployments: this.props.deployments });
		this.updateInterval = setInterval(function() {
			let deployments = this.state.deployments;
			deployments.map((component, index) => {
				axios.get(buildGenericApiUrl('api', `admin/v1/deploy?version=${ component.version }`))
					.then(res => {
						deployments[index] = { ...component, ...res.data };
						
						// XlXi: -_-
						//deployment['status'] = res.data['status'];
						//deployment['message'] = res.data['message'];
						//deployment['progress'] = res.data['progress'];
					});
			});
			this.setState({ deployments: deployments });
		}.bind(this), 2000);
	}
	
	componentWillUnmount() {
		this.Modal.dispose();
		
		clearInterval(this.updateInterval);
	}
	
	render() {
		return (
			<div ref={this.ModalRef} className="modal fade">
				<div className="modal-dialog modal-dialog-centered">
					<div className="modal-content">
						<div className="modal-header">
							<h5 className="modal-title">Deployment Status</h5>
							<button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div className="modal-body">
							{
								this.state.deployments.map((deployment, index) => (
									<>
										<h5 className="mb-0">Deploying { capitalizeFirstLetter(deployment.key) } { deployment.version }</h5>
										<p className="text-muted mb-2">{ deployment.message }</p>
										<div className="progress">
											<div
												className={classNames({
													'progress-bar': true,
													'progress-bar-striped': true,
													'progress-bar-animated': true,
													'bg-primary': ( deployment.status == 'Loading' ),
													'bg-success': ( deployment.status == 'Success' ),
													'bg-danger': ( deployment.status == 'Error' )
												})}
												style={ {width: `${deployment.progress * 100}%`} }
											></div>
										</div>
										{ index != this.state.deployments.length-1 ? <hr /> : null }
									</>
								))
							}
						</div>
					</div>
				</div>
			</div>
		);
	}
}

class DeploymentCard extends Component {
	constructor(props) {
		super(props);
	}
	
	render() {
		return (
			<div className="card mb-2">
				<div className="card-header d-flex">
					<span>{ this.props.name }</span>
					<button className="ms-auto btn-close" onClick={ ()=>this.props.removeDeployment(this.props.index) }></button>
				</div>
				<div className="card-body">
					{ this.props.children }
				</div>
			</div>
		);
	}
}

class RevertDeploymentCard extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loading: true,
			deployments: []
		};
	}
	
	componentDidMount() {
		let deployType = 'Unknown';
		switch(this.props.index)
		{
			case 'client':
				deployType = 'WindowsPlayer'
				break;
			case 'studio':
				deployType = 'Studio'
				break;
		}
		
		let deployHistoryRegex = new RegExp(`New ${deployType} version-[a-zA-Z0-9]{16} at \\d{1,2}\\/\\d{1,2}\\/\\d{4} \\d{1,2}:\\d{1,2}:\\d{1,2} (AM|PM), file version: (\\d+(, )?){4}\\.{3}Done!`, 'g');
		
		axios.get(buildGenericApiUrl('setup', 'DeployHistory.txt'))
			.then(res => {
				let deployments = res.data.split(/\r?\n/).reverse();
				deployments = deployments.filter((deployment) => deployHistoryRegex.test(deployment)).slice(0, 30);
				
				let realDeployments = [];
				
				deployments.map((deployment) => {
					let newDeployment = {
						type: deployType,
						hash: deployment.match(/version-[a-zA-Z0-9]{16}/)[0],
						version: deployment.match(/file version: (\d+(, )?){4}/)[0].replace('file version: ', ''),
						date: deployment.match(/at \d{1,2}\/\d{1,2}\/\d{4} \d{1,2}:\d{1,2}:\d{1,2} (AM|PM)/)[0].replace('at ', '')
					};
					
					realDeployments.push(newDeployment);
				});
				
				this.setState({ loading: false, deployments: realDeployments });
			});
	}
	
	render() {
		return (
			<DeploymentCard name={ this.props.name } index={ this.props.index } removeDeployment={ this.props.removeDeployment }>
				<h5 className="mb-0">Revert Deployment</h5>
				<p className="text-muted">Select a previous deployment below to roll back the { this.props.index } version.</p>
				<select className="form-select mt-2" id="gt-revert-deployment" disabled={ this.state.loading }>
					<option selected>{ this.state.loading ? 'Loading...' : 'None Selected' }</option>
					{
						this.state.deployments.map((deployment, index) => (
							<option value={ deployment.hash } key={ index }>[{ deployment.type } { deployment.version }] [{ deployment.date }] { deployment.hash }</option>
						))
					}
				</select>
			</DeploymentCard>
		);
	}
}

class PushDeploymentCard extends Component {
	constructor(props) {
		super(props);
		this.state = {
			drag: false,
			showRequiredFiles: false,
			files: [],
			options: []
		};
		
		this.dragDropBox = createRef();
		this.dragCounter = 0;
		
		this.neededFiles = [
			'content-fonts.zip',
			'content-music.zip',
			'content-particles.zip',
			'content-sky.zip',
			'content-sounds.zip',
			'content-terrain.zip',
			'content-textures.zip',
			'content-textures2.zip',
			'content-textures3.zip',
			'shaders.zip',
			'redist.zip',
			'Libraries.zip'
		];
		
		this.handleDrag = this.handleDrag.bind(this);
		this.handleDragIn = this.handleDragIn.bind(this);
		this.handleDragOut = this.handleDragOut.bind(this);
		this.handleDrop = this.handleDrop.bind(this);
		this.handleDropUi = this.handleDropUi.bind(this);
		this.fileExists = this.fileExists.bind(this);
		this.removeFile = this.removeFile.bind(this);
		this.setOptions = this.setOptions.bind(this);
	}
	
	componentDidMount() {
		switch(this.props.index)
		{
			case 'client':
				this.neededFiles = this.neededFiles.concat([
					'PlayerPdb.zip',
					'Graphictoria.zip',
					'GraphictoriaPlayerLauncher.exe'
				]);
				break;
			case 'studio':
				this.neededFiles = this.neededFiles.concat([
					'BuiltInPlugins.zip',
					'imageformats.zip',
					'content-scripts.zip',
					'StudioPdb.zip',
					'GraphictoriaStudio.zip',
					'GraphictoriaStudioLauncherBeta.exe'
				]);
				break;
		}
		
		this.setState({ showRequiredFiles: true });
		
		let ddb = this.dragDropBox.current
		ddb.addEventListener('dragenter', this.handleDragIn)
		ddb.addEventListener('dragleave', this.handleDragOut)
		ddb.addEventListener('dragover', this.handleDrag)
		ddb.addEventListener('drop', this.handleDrop)
		
		
	}
	
	componentWillUnmount() {
		let ddb = this.dragDropBox.current
		ddb.removeEventListener('dragenter', this.handleDragIn)
		ddb.removeEventListener('dragleave', this.handleDragOut)
		ddb.removeEventListener('dragover', this.handleDrag)
		ddb.removeEventListener('drop', this.handleDrop)
	}
	
	handleDrag(evt) {
		evt.preventDefault();
		evt.stopPropagation();
	}
	
	handleDragIn(evt) {
		evt.preventDefault();
		evt.stopPropagation();
		this.dragCounter++;
		if (evt.dataTransfer.items && evt.dataTransfer.items.length > 0) {
			this.setState({drag: true});
		}
	}
	
	handleDragOut(evt) {
		evt.preventDefault();
		evt.stopPropagation();
		this.dragCounter--;
		if (this.dragCounter === 0) {
			this.setState({drag: false});
		}
	}
	
	handleDrop(evt) {
		evt.preventDefault();
		evt.stopPropagation();
		this.setState({drag: false});
		if (evt.dataTransfer.files && evt.dataTransfer.files.length > 0) {
			this.handleDropUi(evt.dataTransfer.files);
			evt.dataTransfer.clearData();
			this.dragCounter = 0;
		}
	}
	
	handleDropUi(files) {
		let fileList = this.state.files;
		for (var i = 0; i < files.length; i++) {
			let file = files[i];
			
			if (!file)
				continue;
			
			if(this.fileExists(file.name))
				continue;
			
			fileList.push(file);
		}
		
		this.setState({files: fileList});
		this.props.updateDeploymentFiles(fileList);
	}
	
	fileExists(fileName) {
		return this.state.files.some(file => file.name.toLowerCase() === fileName.toLowerCase());
	}
	
	removeFile(fileName) {
		this.setState(prevState => ({
			files: prevState.files.filter((file) => file.name.toLowerCase() !== fileName.toLowerCase())
		}));
		
		this.props.updateDeploymentFiles(this.state.files);
	}
	
	setOptions(key, value) {
		let options = this.state.options;
		if(value != '')
			options[key] = value;
		else
			delete options[key];
		
		this.setState({ options: options });
		
		this.props.updateDeploymentOptions(options)
	}
	
	render() {
		return (
			<DeploymentCard name={ this.props.name } index={ this.props.index } removeDeployment={ this.props.removeDeployment }>
				<h5 className="mb-0">Deployment Files</h5>
				<p className="text-muted">Drag-and-Drop the necessary files into the box below. Any unneeded files will be discarded when uploading.</p>
				<div className="card bg-secondary mt-3 p-3" ref={ this.dragDropBox }>
					<div>
						{/* XlXi: Reusing game cards here because they were already exactly what I wanted. */}
						{
							this.state.files.length == 0
							?
							<p className="text-muted">Drop files here.</p>
							:
							this.state.files.map((file, index) => {
								let fileType = /(?:\.([^.]+))?$/.exec(file.name)[1].toLowerCase();
								let fileIconClasses = {
									'm-auto': true,
									'fs-1': true,
									'fa-regular': true
								};
								switch(fileType)
								{
									case 'exe':
										fileIconClasses['fa-browser'] = true;
										break;
									case 'zip':
										fileIconClasses['fa-file-zipper'] = true;
										break;
									default:
										fileIconClasses['fa-file'] = true;
										break;
								}
								
								return (
									<div className="graphictoria-item-card graphictoria-game-card">
										<div className="card m-2" data-bs-toggle="tooltip" data-bs-placement="top" title={ file.name }>
											<div className="bg-light d-flex p-3">
												<i className={classNames(fileIconClasses)}></i>
											</div>
											<div className="p-2">
												<p className="text-truncate">{ file.name }</p>
												<button className="btn btn-sm btn-danger mt-1 w-100" onClick={ ()=>this.removeFile(file.name) }>Remove</button>
											</div>
										</div>
									</div>
								);
							})
						}
					</div>
					{
						this.state.showRequiredFiles
						?
						<>
							<hr />
							<h5>Needed Files</h5>
							<div className="small">
								{
									this.neededFiles.map((fileName) => {
										let fileExists = this.fileExists(fileName);
										
										return (
											<p className={classNames({
												'text-success': fileExists,
												'text-danger': !fileExists
											})}>{ fileName }</p>
										);
									})
								}
							</div>
						</>
						:
						null
					}
				</div>
				{
					this.props.index == 'client'
					?
					<>
						<h5 className="mb-0 mt-3">Optional Configuration</h5>
						<p className="text-muted mb-3">Only change if you've updated the security settings on the client/rcc. Shutting down game servers will delay deployment by 10 minutes.</p>
						<div className="form-check form-switch">
							<input className="form-check-input" type="checkbox" role="switch" id="gt-shut-down-servers" />
							<label className="form-check-label" htmlFor="gt-shut-down-servers">Shut down game servers.</label>
						</div>
						<label htmlFor="gt-rcc-security-key" className="form-label mt-2">Update RCC Security Key</label>
						<input type="text" id="gt-rcc-security-key" className="form-control" placeholder="New RCC Security Key" onChange={ changeEvent => this.setOptions('rccAccessKey', changeEvent.target.value) } />
						<label htmlFor="gt-rcc-security-key" className="form-label mt-2">Update Version Compatibility Salt</label>
						<input type="text" id="gt-rcc-security-key" className="form-control" placeholder="New Version Compatibility Salt" onChange={ changeEvent => this.setOptions('versionCompatiblityFuzzyKey', changeEvent.target.value) } />
					</>
					:
					null
				}
			</DeploymentCard>
		);
	}
}

// https://stackoverflow.com/questions/1026069/how-do-i-make-the-first-letter-of-a-string-uppercase-in-javascript
function capitalizeFirstLetter(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

class Deployer extends Component {
	constructor(props) {
		super(props);
		this.state = {
			showModal: false,
			deployType: 'deploy',
			showTypeSwitchError: false,
			deploying: false,
			deployments: []
		};
		
		this.visibleModal = null;
		
		this.updateDeploymentFiles = this.updateDeploymentFiles.bind(this);
		this.deploymentExists = this.deploymentExists.bind(this);
		this.getDeployment = this.getDeployment.bind(this);
		this.createDeployment = this.createDeployment.bind(this);
		this.removeDeployment = this.removeDeployment.bind(this);
		this.trySetDeployType = this.trySetDeployType.bind(this);
		this.uploadDeploymentFiles = this.uploadDeploymentFiles.bind(this);
		this.uploadDeployments = this.uploadDeployments.bind(this);
		this.setModal = this.setModal.bind(this);
	}
	
	updateDeploymentFiles(key, files) {
		if(this.state.deploying) // XlXi: Prevent messing with the component when we're deploying.
			return;
		
		let deployment = this.getDeployment(key);
		deployment['files'] = files;
	}
	
	updateDeploymentOptions(key, options) {
		if(this.state.deploying) // XlXi: Prevent messing with the component when we're deploying.
			return;
		
		let deployment = this.getDeployment(key);
		deployment['extraOptions'] = options;
	}
	
	deploymentExists(key) {
		return this.state.deployments.some(deployment => deployment.key === key);
	}
	
	getDeployment(key) {
		return this.state.deployments.find((deployment) => deployment.key === key);
	}
	
	createDeployment(key) {
		if(this.state.deploying) // XlXi: Prevent messing with the component when we're deploying.
			return;
		
		let newElement = {
			key: key,
			name: capitalizeFirstLetter(this.state.deployType) + ' ' + capitalizeFirstLetter(key),
			files: [],
			extraOptions: []
		};
		
		this.setState(prevState => ({
			deployments: [...prevState.deployments, newElement]
		}));
	}
	
	removeDeployment(key) {
		if(this.state.deploying) // XlXi: Prevent messing with the component when we're deploying.
			return;
		
		this.setState(prevState => ({
			deployments: prevState.deployments.filter((deployment) => deployment.key !== key)
		}));
	}
	
	trySetDeployType(evt, type) {
		if(this.state.deploying) // XlXi: Prevent messing with the component when we're deploying.
			return;
		
		if(!evt.target.checked)
			return;
		
		if(this.state.deployType != type && this.state.deployments.length != 0)
		{
			this.setState({ showTypeSwitchError: true });
			return;
		}
		
		this.setState({ deployType: type, showTypeSwitchError: false });
	}
	
	uploadDeploymentFiles(key, version) {
		let deployment = this.getDeployment(key);
		let bodyFormData = new FormData();
		
		deployment.files.map((file) => {
			bodyFormData.append('file[]', file);
		});
		
		Object.keys(deployment.extraOptions).forEach(function(key, index) {
			bodyFormData.append(key, deployment.extraOptions[key]);
		});
		
		axios.post(
			buildGenericApiUrl('api', `admin/v1/deploy/${ deployment.version }`),
			bodyFormData
		);
	}
	
	uploadDeployments() {
		if(this.state.deploying) // XlXi: Prevent multiple deployments of the same files.
			return;
		
		this.setState({ deploying: true });
		
		let requests = 0;
		this.state.deployments.map((component, index) => {
			axios.get(buildGenericApiUrl('api', `admin/v1/deploy?type=deploy&app=${ component.key }`))
				.then(res => {
					requests++;
					
					this.state.deployments[index] = {...component, ...res.data};
					
					if(requests == this.state.deployments.length)
						this.setModal(<DeploymentUploadModal setModal={ this.setModal } deployments={ this.state.deployments } />);
					
					this.uploadDeploymentFiles(component.key, component.version);
				});
		});
		
		//this.setState({ deployments: [] });
	}
	
	setModal(modal = null) {
		this.visibleModal = modal;
		
		if(modal) {
			this.setState({'showModal': true});
		} else {
			this.setState({'showModal': false});
		}
	}
	
	render() {
		return (
			<>
				<h5>Deployment Options</h5>
				<div className="d-block">
					<div className="btn-group mb-1">
						<input type="radio" className="btn-check" name="gt-deployment-type" id="gt-deployment-deploy" autoComplete="off" onChange={ (e)=>this.trySetDeployType(e, 'deploy') } checked={ this.state.deployType == 'deploy' } />
						<label className="btn btn-sm btn-outline-primary" htmlFor="gt-deployment-deploy">Deploy</label>
						<input type="radio" className="btn-check" name="gt-deployment-type" id="gt-deployment-revert" autoComplete="off" onChange={ (e)=>this.trySetDeployType(e, 'revert') } checked={ this.state.deployType == 'revert' } />
						<label className="btn btn-sm btn-outline-primary" htmlFor="gt-deployment-revert">Revert</label>
					</div>
					<br />
					<button className="btn btn-sm btn-success" onClick={ ()=>this.createDeployment('client') } disabled={ this.deploymentExists('client') }>Deploy Client</button>
					<button className="btn btn-sm btn-primary" onClick={ ()=>this.createDeployment('studio') } disabled={ this.deploymentExists('studio') }>Deploy Studio</button>
				</div>
				<hr />
				{
					this.state.showTypeSwitchError ?
					<div className="alert alert-danger graphictoria-alert graphictoria-error-popup">Remove your { this.state.deployType == 'deploy' ? 'deployments' : 'reversions' } to change the uploader type.</div>
					:
					null
				}
				<h5>{ this.state.deployType == 'deploy' ? 'Staged Deployments' : 'Revert Deployments' }</h5>
				{
					this.state.deployments.length == 0 ?
					<p className="text-muted">No deployments are selected.</p>
					:
					this.state.deployments.map((component) => {
						// XlXi: surely theres a better way to do this.....
						switch(this.state.deployType)
						{
							case 'deploy':
								return <PushDeploymentCard name={ component.name } index={ component.key } key={ component.key } removeDeployment={ this.removeDeployment } updateDeploymentFiles={ (files)=>this.updateDeploymentFiles(component.key, files) } updateDeploymentOptions={ (options)=>this.updateDeploymentOptions(component.key, options) } />
							case 'revert':
								return <RevertDeploymentCard name={ component.name } index={ component.key } key={ component.key } removeDeployment={ this.removeDeployment } />
						}
					})
				}
				<hr />
				<button className="btn btn-primary" onClick={ this.uploadDeployments } disabled={ this.state.deploying || this.state.deployments.length == 0 }>{ this.state.deployType == 'deploy' ? 'Deploy' : 'Revert Deployment' }</button>
				{ this.state.showModal ? this.visibleModal : null }
			</>
		);
	}
}

export default Deployer;