/*
	Copyright © XlXi 2022
*/

import { createRef, Component } from 'react';
import axios from 'axios';

import classNames from 'classnames/bind';
import ProgressiveImage from './ProgressiveImage';
import { buildGenericApiUrl } from '../util/HTTP.js';

axios.defaults.withCredentials = true;

const itemId = 'vb-item';

class PurchaseConfirmationModal extends Component {
	constructor(props) {
		super(props);
		this.state = {
			buttonDisabled: true
		};
		
		this.ModalRef = createRef();
		this.Modal = null;
		
		this.purchaseAsset = this.purchaseAsset.bind(this);
	}
	
	componentDidMount() {
		let itemElement = document.getElementById(itemId);
		if(itemElement) {
			this.setState({
				assetId: itemElement.getAttribute('data-asset-id'),
				assetName: itemElement.getAttribute('data-asset-name'),
				assetCreator: itemElement.getAttribute('data-asset-creator'),
				assetType: itemElement.getAttribute('data-asset-type'),
				assetPrice: parseInt(itemElement.getAttribute('data-asset-price')),
				assetThumbnail: itemElement.getAttribute('data-asset-thumbnail-2d'),
				userTokens: parseInt(itemElement.getAttribute('data-user-currency'))
			});
		}
		
		this.Modal = new Bootstrap.Modal(this.ModalRef.current);
		this.Modal.show();
		
		this.ModalRef.current.addEventListener('hidden.bs.modal', (event) => {
			this.props.setModal(null);
		})
		
		setTimeout(function(){
			this.setState({buttonDisabled: false});
		}.bind(this), 1000);
	}
	
	componentWillUnmount() {
		this.Modal.dispose();
	}
	
	purchaseAsset() {
		console.log('hi');
		if(this.state.buttonDisabled) return;
		
		this.setState({buttonDisabled: true});
		
		axios.post(buildGenericApiUrl('api', `shop/v1/purchase/${this.state.assetId}?expectedPrice=${this.state.assetPrice}`))
			.then(res => {
				if(res.data.success)
				{
					this.props.setModal(<PurchaseSuccessModal setModal={this.props.setModal} />);
				}
				else if(res.data.userFacingMessage != null)
				{
					this.props.setModal(
						<PurchaseErrorModal setModal={this.props.setModal}>
							<p>{ res.data.userFacingMessage }</p>
						</PurchaseErrorModal>
					);
				}
				else
				{
					let oldPrice = this.state.assetPrice;
					let newTokens = (this.state.userTokens - res.data.priceInTokens);
					this.setState({assetPrice: res.data.priceInTokens, buttonDisabled: false});
					this.props.setModal(
						<PurchaseErrorModal setModal={ this.props.setModal } purchaseAsset={ this.purchaseAsset } newTokens={ newTokens }>
							<p>This item's price has changed from <span className="virtubrick-tokens">{ oldPrice }</span> to <span className="virtubrick-tokens">{ res.data.priceInTokens }</span>. {newTokens >= 0 ? 'Would you still like to purchase?' : null }</p>
						</PurchaseErrorModal>
					);
				}
			})
			.catch(err => {
				this.props.setModal(
					<PurchaseErrorModal setModal={this.props.setModal}>
						<p>An unexpected error occurred while purchasing this item.</p>
					</PurchaseErrorModal>
				);
			});
	}
	
	render() {
		return (
			<div ref={this.ModalRef} className="modal fade">
				<div className="modal-dialog modal-dialog-centered">
					<div className="modal-content text-center">
						<div className="modal-header">
							<h5 className="modal-title">Purchase Item</h5>
							<button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div className="modal-body d-flex flex-column">
							<p>Would you like to purchase the { this.state.assetType } <strong>{ this.state.assetName }</strong> from { this.state.assetCreator } for <span className="virtubrick-tokens">{ this.state.assetPrice }</span>?</p>
							<ProgressiveImage
								src={ this.state.assetThumbnail } 
								placeholderImg={ buildGenericApiUrl('www', 'images/busy/asset.png') }
								alt={ this.state.assetName }
								width='240'
								height='240'
								className='mx-auto img-fluid'
							/>
						</div>
						<div className="modal-footer flex-column">
							<div className="mx-auto">
								<button className="btn btn-success" disabled={ this.state.buttonDisabled } onClick={ this.purchaseAsset }>Purchase</button>
								&nbsp;
								<button className="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
							</div>
							<p className="text-muted pt-1">You will have <span className="virtubrick-tokens">{ Math.max(0, (this.state.userTokens - this.state.assetPrice)) }</span> after this purchase.</p>
						</div>
					</div>
				</div>
			</div>
		);
	}
}

class PurchaseErrorModal extends Component {
	constructor(props) {
		super(props);
		this.state = {
			buttonDisabled: true
		};
		
		this.ModalRef = createRef();
		this.Modal = null;
	}
	
	componentDidMount() {
		this.Modal = new Bootstrap.Modal(this.ModalRef.current);
		this.Modal.show();
		
		this.ModalRef.current.addEventListener('hidden.bs.modal', (event) => {
			this.props.setModal(null);
		})
		
		setTimeout(function(){
			this.setState({buttonDisabled: false});
		}.bind(this), 1000);
	}
	
	componentWillUnmount() {
		this.Modal.dispose();
	}
	
	render() {
		return (
			<div ref={this.ModalRef} className="modal fade">
				<div className="modal-dialog modal-dialog-centered">
					<div className="modal-content text-center">
						<div className="modal-header">
							<h5 className="modal-title">Error Occurred</h5>
							<button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div className="modal-body">
							{ this.props.children }
						</div>
						<div className="modal-footer flex-column">
							{
								this.props.purchaseAsset && this.props.newTokens >= 0 ?
								<>
									<div className="mx-auto">
										<button className="btn btn-success" disabled={ this.state.buttonDisabled } onClick={ () => { this.setState({buttonDisabled: true}); this.props.purchaseAsset(); } }>Purchase</button>
										&nbsp;
										<button className="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
									</div>
									<p className="text-muted pt-1">You will have <span className="virtubrick-tokens">{ this.props.newTokens }</span> after this purchase.</p>
								</>
								:
								<button className="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
							}
						</div>
					</div>
				</div>
			</div>
		);
	}
}

class PurchaseSuccessModal extends Component {
	constructor(props) {
		super(props);
		this.state = {
			
		};
		
		this.ModalRef = createRef();
		this.Modal = null;
	}
	
	componentDidMount() {
		let itemElement = document.getElementById(itemId);
		if(itemElement) {
			this.setState({
				assetName: itemElement.getAttribute('data-asset-name'),
				assetPrice: parseInt(itemElement.getAttribute('data-asset-price'))
			});
		}
		
		this.Modal = new Bootstrap.Modal(
			this.ModalRef.current,
			{
				backdrop: 'static',
				keyboard: false
			}
		);
		this.Modal.show();
		
		setTimeout(function(){
			window.location.reload();
		}, 2000);
	}
	
	componentWillUnmount() {
		this.Modal.dispose();
	}
	
	render() {
		return (
			<div ref={this.ModalRef} className="modal fade">
				<div className="modal-dialog modal-dialog-centered">
					<div className="modal-content text-center">
						<div className="modal-header">
							<h5 className="modal-title">Purchase Successful</h5>
						</div>
						<div className="modal-body">
							<p>You have successfully purchased { this.state.assetName } for <span className="virtubrick-tokens">{ this.state.assetPrice }</span>!</p>
						</div>
					</div>
				</div>
			</div>
		);
	}
}

class NotEnoughTokensModal extends Component {
	constructor(props) {
		super(props);
		this.state = {
			
		};
		
		this.ModalRef = createRef();
		this.Modal = null;
	}
	
	componentDidMount() {
		let itemElement = document.getElementById(itemId);
		if(itemElement) {
			this.setState({
				assetPrice: parseInt(itemElement.getAttribute('data-asset-price')),
				userTokens: parseInt(itemElement.getAttribute('data-user-currency'))
			});
		}
		
		this.Modal = new Bootstrap.Modal(this.ModalRef.current);
		this.Modal.show();
		
		this.ModalRef.current.addEventListener('hidden.bs.modal', (event) => {
			this.props.setModal(null);
		})
	}
	
	componentWillUnmount() {
		this.Modal.dispose();
	}
	
	render() {
		return (
			<div ref={this.ModalRef} className="modal fade">
				<div className="modal-dialog modal-dialog-centered">
					<div className="modal-content text-center">
						<div className="modal-header">
							<h5 className="modal-title">Insufficient Funds</h5>
							<button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div className="modal-body">
							<p>You need <span className="virtubrick-tokens">{ this.state.assetPrice - this.state.userTokens }</span> more to purchase this item.</p>
						</div>
						<div className="modal-footer">
							<button className="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
						</div>
					</div>
				</div>
			</div>
		);
	}
}

class PurchaseButton extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loaded: false,
			showModal: false,
			canPurchase: false
		};
		
		this.visibleModal = null;
		
		this.showPrompt = this.showPrompt.bind(this);
		this.setModal = this.setModal.bind(this);
	}
	
	componentDidMount() {
		let itemElement = document.getElementById(itemId);
		if(itemElement) {
			this.setState({
				assetOnSale: (itemElement.getAttribute('data-asset-on-sale') === '1'),
				canAfford: (itemElement.getAttribute('data-can-afford') === '1'),
				owned: (itemElement.getAttribute('data-owned') === '1')
			}, function(){
				let canPurchase = (!this.state.owned && this.state.assetOnSale);
				this.setState({canPurchase: canPurchase});
			});
		}
		
		this.setState({loaded: true});
	}
	
	showPrompt() {
		if(this.state.canAfford)
			this.setModal(<PurchaseConfirmationModal setModal={ this.setModal } />);
		else
			this.setModal(<NotEnoughTokensModal setModal={ this.setModal } />);
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
			this.state.loaded
			?
			<>
				<button
					className={classNames({
						'px-5': true,
						'btn': true,
						'btn-lg': true,
						'btn-success': this.state.canPurchase,
						'btn-secondary': !this.state.canPurchase
					})}
					disabled={ !this.state.canPurchase ? true : null }
					onClick={ this.showPrompt }
				>
					{
						this.state.canPurchase
						?
						'Buy'
						:
						this.state.owned
						?
						'Owned'
						:
						'Offsale'
					}
				</button>
				
				{ this.state.showModal ? this.visibleModal : null }
			</>
			:
			<button className="px-5 btn btn-lg btn-success" disabled={true}>Buy</button>
		);
	}
}

export default PurchaseButton;