/*
	Graphictoria 5 (https://gtoria.net)
	Copyright © XlXi 2022
*/

import { createRef, Component } from 'react';

import classNames from 'classnames/bind';

const itemId = 'gt-item';

class PurchaseConfirmationModal extends Component {
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
				assetCreator: itemElement.getAttribute('data-asset-creator'),
				assetType: itemElement.getAttribute('data-asset-type'),
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
							<h5 className="modal-title">Purchase Item</h5>
							<button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div className="modal-body d-flex flex-column">
							<p>Would you like to purchase the { this.state.assetType } "<strong>{ this.state.assetName }</strong>" from { this.state.assetCreator } for <strong style={{'color': '#e59800', 'fontWeight': 'bold'}}><img src="/images/symbols/token.svg" height="16" width="16" className="img-fluid" style={{'marginTop': '-1px'}} />{ this.state.assetPrice }</strong>?</p>
							<img src="/images/testing/hat.png" width="240" height="240" alt="{ this.state.assetName }" className="mx-auto my-2 img-fluid" />
						</div>
						<div className="modal-footer flex-column">
							<div className="mx-auto">
								<button className="btn btn-success">Purchase</button>
								&nbsp;
								<button className="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
							</div>
							<p className="text-muted pt-1">You will have <strong style={{'color': '#e59800', 'fontWeight': 'bold'}}><img src="/images/symbols/token.svg" height="16" width="16" className="img-fluid" style={{'marginTop': '-1px'}} />{ Math.max(0, (this.state.userTokens - this.state.assetPrice)) }</strong> after this purchase.</p>
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
							<p>You need <strong style={{'color': '#e59800', 'fontWeight': 'bold'}}><img src="/images/symbols/token.svg" height="16" width="16" className="img-fluid" style={{'marginTop': '-1px'}} />{ this.state.assetPrice - this.state.userTokens }</strong> more to purchase this item.</p>
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
			showModal: false
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
				canAfford: (itemElement.getAttribute('data-can-afford') === '1')
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
			<>
				<button
					className={classNames({
						'px-5': true,
						'btn': true,
						'btn-lg': true,
						'btn-success': this.state.assetOnSale,
						'btn-secondary': !this.state.assetOnSale
					})}
					disabled={ !(this.state.loaded && this.state.assetOnSale) ? true : null }
					onClick={ this.showPrompt }
				>
					{ (!this.state.loaded || this.state.assetOnSale) ? 'Buy' : 'Offsale' }
				</button>
				
				{ this.state.showModal ? this.visibleModal : null }
			</>
		);
	}
}

export default PurchaseButton;