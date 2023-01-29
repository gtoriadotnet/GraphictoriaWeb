/*
	Copyright © XlXi 2022
*/

import { Component, createElement, createRef } from 'react';
import axios from 'axios';

import classNames from 'classnames/bind';

import { buildGenericApiUrl } from '../util/HTTP.js';
import Loader from './Loader';

axios.defaults.withCredentials = true;

const assetTypes = [
	{
		assetTypeId: 1,
		name: 'Image',
		type: 'fileupload',
		extra: <p>PNG, JPG, JPEG, and other common image formats are supported.</p>,
		sellable: false
	},
	{
		assetTypeId: 4,
		name: 'Mesh',
		type: 'fileupload',
		extra: <p>Your mesh can be <b>obj</b> or Roblox's <b>mesh</b> format.</p>,
		sellable: false
	},
	{
		assetTypeId: 5,
		name: 'Lua',
		type: 'text',
		sellable: false
	},
	{
		assetTypeId: 6,
		name: 'HTML',
		type: 'text',
		sellable: false
	},
	{
		assetTypeId: 7,
		name: 'Text',
		type: 'text',
		sellable: false
	},
	{
		assetTypeId: 8,
		name: 'Hat',
		type: 'fileupload',
		sellable: true
	},
	{
		assetTypeId: 17,
		name: 'Head',
		type: 'fileupload',
		extra: <p>Heads are SpecialMeshes. Export it from studio, do not upload the mesh file here.</p>,
		sellable: true
	},
	{
		assetTypeId: 18,
		name: 'Face',
		type: 'fileupload',
		extra: <p>Faces are image files. The XML will be automatically generated.</p>,
		sellable: true
	},
	{
		assetTypeId: 19,
		name: 'Gear',
		type: 'fileupload',
		sellable: true
	},
	{
		assetTypeId: 27,
		name: 'Torso',
		type: 'packagepart',
		extra: <p>Overlay ID displays atop clothing, base ID displays under clothing.</p>,
		sellable: false
	},
	{
		assetTypeId: 28,
		name: 'Right Arm',
		type: 'packagepart',
		extra: <p>Overlay ID displays atop clothing, base ID displays under clothing.</p>,
		sellable: false
	},
	{
		assetTypeId: 29,
		name: 'Left Arm',
		type: 'packagepart',
		extra: <p>Overlay ID displays atop clothing, base ID displays under clothing.</p>,
		sellable: false
	},
	{
		assetTypeId: 30,
		name: 'Left Leg',
		type: 'packagepart',
		extra: <p>Overlay ID displays atop clothing, base ID displays under clothing.</p>,
		sellable: false
	},
	{
		assetTypeId: 31,
		name: 'Right Leg',
		type: 'packagepart',
		extra: <p>Overlay ID displays atop clothing, base ID displays under clothing.</p>,
		sellable: false
	},
	{
		assetTypeId: 32,
		name: 'Package',
		type: 'text',
		extra: <p>Asset IDs for package. Example: 1;2;3;4;5</p>,
		sellable: true
	},
	{
		assetTypeId: 37,
		name: 'Code',
		type: 'text',
		sellable: false
	}
];

class ManualAssetUploadModel extends Component {
	constructor(props) {
		super(props);
		this.state = {
			forSale: false,
			requestInputs: {
				name: 'New Asset',
				description: this.props.TypeName + ' Asset',
				'roblox-id': 0,
				'on-sale': false,
				price: 0
			}
		};
		
		this.forSaleRef = createRef();
		
		this.pushInput = this.pushInput.bind(this);
		this.updateContentInputFile = this.updateContentInputFile.bind(this);
		this.updateContentInputText = this.updateContentInputText.bind(this);
		this.updateInput = this.updateInput.bind(this);
		this.uploadAsset = this.uploadAsset.bind(this);
	}
	
	componentDidMount() {
		this.pushInput('asset-type-id', this.props.TypeId);
		
		if(this.props.UploadType == 'packagepart')
		{
			this.pushInput('mesh-id', 0);
			this.pushInput('overlay-id', 0);
			this.pushInput('base-id', 0);
		}
	}
	
	pushInput(name, value) {
		this.setState((state, props) => ({
			requestInputs: { ...state.requestInputs, [name]: value }
		}));
	}
	
	updateContentInputFile(value) {
		this.pushInput('content', value);
	}
	
	updateContentInputText(value) {
		const file = (value != '' && new Blob([ value ], { type: 'text/plain' }));
		this.pushInput('content', file);
	}
	
	updateInput(e, prop='value') {
		this.pushInput(e.target.id, e.target[prop]);
	}
	
	uploadAsset() {
		let { requestInputs } = this.state;
		
		this.props.setLoad(true);
		
		if(!requestInputs.content && this.props.UploadType != 'packagepart')
		{
			this.props.flashError('Asset content cannot be blank!');
			this.props.setLoad(false);
			return;
		}
		
		let bodyFormData = new FormData();
		Object.keys(requestInputs).map(key => {
			let val = requestInputs[key];
			if(typeof(val) == 'boolean')
				val = val ? 1 : 0;
			
			bodyFormData.append(key, val);
		});
		
		axios.post(buildGenericApiUrl('api', 'admin/v1/manual-asset-upload'), bodyFormData)
			.then(res => {
				const data = res.data;
				
				this.props.flashSuccess(data.message, data.assetId);
				this.props.setLoad(false);
			})
			.catch(err => {
				const data = err.response.data;
				
				this.props.flashError(data.errors ? data.errors[0].message : 'An unknown error occurred.');
				this.props.setLoad(false);
			});
	}
	
	render() {
		let { TypeName, UploadType, Extra, Sellable } = this.props;
		
		return (
			<>
				<h4>Create a { TypeName }</h4>
				<div>
					<div className="mb-3">
						{ Extra }
						{
							UploadType == 'fileupload'
							?
							<>
								<label for="content" className="form-label">Find your { TypeName }:</label>
								<input className="form-control mb-2" type="file" id="content" onChange={ (e) => this.updateContentInputFile(e.target.files[0]) } />
							</>
							:
							UploadType == 'packagepart'
							?
							<>
								<label for="mesh-id" className="form-label">Mesh ID:</label>
								<input className="form-control mb-2" type="number" id="mesh-id" value={ this.state.requestInputs['mesh-id'] } onChange={ this.updateInput } />
								<label for="overlay-id" className="form-label">Overlay Texture ID (if applicable):</label>
								<input className="form-control mb-2" type="number" id="overlay-id" value={ this.state.requestInputs['overlay-id'] } onChange={ this.updateInput } />
								<label for="base-id" className="form-label">Base Texture ID (if applicable):</label>
								<input className="form-control mb-2" type="number" id="base-id" value={ this.state.requestInputs['base-id'] } onChange={ this.updateInput } />
							</>
							:
							UploadType == 'text'
							&&
							<>
								<label for="content" className="form-label">{ TypeName } Contents:</label>
								<textarea className="form-control mb-2" type="text" id="content" onChange={ (e) => this.updateContentInputText(e.target.value) }></textarea>
							</>
						}
						<label for="name" className="form-label">{ TypeName } Name:</label>
						<input className="form-control mb-2" type="text" id="name" value={ this.state.requestInputs.name } onChange={ this.updateInput } />
						<label for="description" className="form-label">{ TypeName } Description:</label>
						<textarea className="form-control mb-2" type="text" id="description" value={ this.state.requestInputs.description } onChange={ this.updateInput }></textarea>
						<label for="roblox-id" className="form-label">Roblox Asset ID (if applicable):</label>
						<input className="form-control" type="number" id="roblox-id" value={ this.state.requestInputs['roblox-id'] } onChange={ this.updateInput } />
						{
							Sellable
							&&
							<>
								<hr />
								<h4>Sell this Item</h4>
								<div className="form-check">
									<input className="form-check-input" type="checkbox" value={ this.state.requestInputs['on-sale'] } id="on-sale" onChange={ (e) => this.updateInput(e, 'checked') } />
									<label className="form-check-label" for="on-sale">Sell this Item</label>
								</div>
								{
									this.state.requestInputs['on-sale']
									&&
									<div className="input-group mb-2">
										<span className="input-group-text px-1 pe-0">
											<p className="virtubrick-tokens">&nbsp;</p>
										</span>
										<input className="form-control" type="number" id="price" value={ this.state.requestInputs.price } min="0" max="999999999" placeholder="Price" onChange={ this.updateInput } />
									</div>
								}
							</>
						}
					</div>
					<button className="btn btn-success px-5" onClick={ this.uploadAsset }>Upload</button>
				</div>
			</>
		)
	}
}

class ManualAssetUpload extends Component {
	constructor(props) {
		super(props);
		this.state = {
			createModelLoaded: false,
			currentTabTypeId: 0,
			tabKey: 0,
			loading: false
		};
		
		this.findAssetType = this.findAssetType.bind(this);
		this.setLoad = this.setLoad.bind(this);
		this.flashError = this.flashError.bind(this);
		this.flashSuccess = this.flashSuccess.bind(this);
		this.navigateAssetType = this.navigateAssetType.bind(this);
	}
	
	componentDidMount()
	{
		this.navigateAssetType(1);
	}
	
	findAssetType(typeId) {
		return assetTypes.find(obj => {
			return obj.assetTypeId === typeId
		});
	}
	
	setLoad(loading) {
		this.setState({ loading: loading });
	}
	
	flashError(message) {
		this.setState({ errorMessage: message });
		setTimeout(function(){
			this.setState({ errorMessage: null });
		}.bind(this), 3000);
	}
	
	flashSuccess(message, assetId) {
		this.setState({ successMessage: message, successId: assetId });
		setTimeout(function(){
			this.setState({ successMessage: null, successId: null });
		}.bind(this), 10000);
	}
	
	navigateAssetType(typeId)
	{
		if(this.state.loading) return;
		
		let data = this.findAssetType(typeId);
		
		this.activeModel = createElement(
			ManualAssetUploadModel,
			{
				TypeId: data.assetTypeId,
				TypeName: data.name,
				UploadType: data.type,
				Extra: data.extra,
				Sellable: data.sellable,
				setLoad: this.setLoad,
				flashError: this.flashError,
				flashSuccess: this.flashSuccess,
				key: this.state.tabKey
			}
		);
		
		this.setState({ createModelLoaded: true, currentTabTypeId: data.assetTypeId, tabKey: this.state.tabKey+1 });
	}
	
	render() {
		return (
			<>
				<div className="col-2 pe-0">
					<ul className="nav nav-tabs flex-column">
						{
							assetTypes.map(({ assetTypeId, name }) =>
								<li className="nav-item">
									<button className={classNames({ 'nav-link': true, 'active': (assetTypeId == this.state.currentTabTypeId) })} disabled={ this.state.loading } onClick={ () => this.navigateAssetType(assetTypeId) }>{ name }</button>
								</li>
							)
						}
					</ul>
				</div>
				<div className="col-10 ps-0">
					{
						this.state.successMessage
						&&
						<div className="alert alert-success virtubrick-alert virtubrick-error-popup">{ this.state.successMessage } <a className="text-decoration-none" href={ buildGenericApiUrl('www', `shop/${ this.state.successId }`) }>Click Here</a></div>
					}
					{
						this.state.errorMessage
						&&
						<div className="alert alert-danger virtubrick-alert virtubrick-error-popup">{ this.state.errorMessage }</div>
					}
					<div className="card p-3 vb-card-navconnector">
						{
							this.state.loading
							&&
							<div className="virtubrick-shop-overlay">
								<Loader />
							</div>
						}
						{
							!this.state.createModelLoaded
							?
							<Loader />
							:
							this.activeModel
						}
					</div>
				</div>
			</>
		);
	}
}

export default ManualAssetUpload;