/*
	Copyright © XlXi 2022
*/

import { Component, createRef, createElement } from 'react';
import axios from 'axios';

import classNames from 'classnames/bind';

import { buildGenericApiUrl } from '../util/HTTP.js';
import ProgressiveImage from './ProgressiveImage';
import Loader from './Loader';

import ThumbnailTool from './ThumbnailTool';

axios.defaults.withCredentials = true;

const brickColors = [
	{id: 1, name: 'White'},
	{id: 2, name: 'Grey'},
	{id: 3, name: 'Light yellow'},
	{id: 5, name: 'Brick yellow'},
	{id: 6, name: 'Light green (Mint)'},
	{id: 9, name: 'Light reddish violet'},
	{id: 11, name: 'Pastel Blue'},
	{id: 12, name: 'Light orange brown'},
	{id: 18, name: 'Nougat'},
	{id: 21, name: 'Bright red'},
	{id: 22, name: 'Med. reddish violet'},
	{id: 23, name: 'Bright blue'},
	{id: 24, name: 'Bright yellow'},
	{id: 25, name: 'Earth orange'},
	{id: 26, name: 'Black'},
	{id: 27, name: 'Dark grey'},
	{id: 28, name: 'Dark green'},
	{id: 29, name: 'Medium green'},
	{id: 36, name: 'Lig. Yellowich orange'},
	{id: 37, name: 'Bright green'},
	{id: 38, name: 'Dark orange'},
	{id: 39, name: 'Light bluish violet'},
	{id: 40, name: 'Transparent'},
	{id: 41, name: 'Tr. Red'},
	{id: 42, name: 'Tr. Lg blue'},
	{id: 43, name: 'Tr. Blue'},
	{id: 44, name: 'Tr. Yellow'},
	{id: 45, name: 'Light blue'},
	{id: 47, name: 'Tr. Flu. Reddish orange'},
	{id: 48, name: 'Tr. Green'},
	{id: 49, name: 'Tr. Flu. Green'},
	{id: 50, name: 'Phosph. White'},
	{id: 100, name: 'Light red'},
	{id: 101, name: 'Medium red'},
	{id: 102, name: 'Medium blue'},
	{id: 103, name: 'Light grey'},
	{id: 104, name: 'Bright violet'},
	{id: 105, name: 'Br. yellowish orange'},
	{id: 106, name: 'Bright orange'},
	{id: 107, name: 'Bright bluish green'},
	{id: 108, name: 'Earth yellow'},
	{id: 110, name: 'Bright bluish violet'},
	{id: 111, name: 'Tr. Brown'},
	{id: 112, name: 'Medium bluish violet'},
	{id: 113, name: 'Tr. Medi. reddish violet'},
	{id: 115, name: 'Med. yellowish green'},
	{id: 116, name: 'Med. bluish green'},
	{id: 118, name: 'Light bluish green'},
	{id: 119, name: 'Br. yellowish green'},
	{id: 120, name: 'Lig. yellowish green'},
	{id: 121, name: 'Med. yellowish orange'},
	{id: 123, name: 'Br. reddish orange'},
	{id: 124, name: 'Bright reddish violet'},
	{id: 125, name: 'Light orange'},
	{id: 126, name: 'Tr. Bright bluish violet'},
	{id: 127, name: 'Gold'},
	{id: 128, name: 'Dark nougat'},
	{id: 131, name: 'Silver'},
	{id: 133, name: 'Neon orange'},
	{id: 134, name: 'Neon green'},
	{id: 135, name: 'Sand blue'},
	{id: 136, name: 'Sand violet'},
	{id: 137, name: 'Medium orange'},
	{id: 138, name: 'Sand yellow'},
	{id: 140, name: 'Earth blue'},
	{id: 141, name: 'Earth green'},
	{id: 143, name: 'Tr. Flu. Blue'},
	{id: 145, name: 'Sand blue metallic'},
	{id: 146, name: 'Sand violet metallic'},
	{id: 147, name: 'Sand yellow metallic'},
	{id: 148, name: 'Dark grey metallic'},
	{id: 149, name: 'Black metallic'},
	{id: 150, name: 'Light grey metallic'},
	{id: 151, name: 'Sand green'},
	{id: 153, name: 'Sand red'},
	{id: 154, name: 'Dark red'},
	{id: 157, name: 'Tr. Flu. Yellow'},
	{id: 158, name: 'Tr. Flu. Red'},
	{id: 168, name: 'Gun metallic'},
	{id: 176, name: 'Red flip/flop'},
	{id: 178, name: 'Yellow flip/flop'},
	{id: 179, name: 'Silver flip/flop'},
	{id: 180, name: 'Curry'},
	{id: 190, name: 'Fire Yellow'},
	{id: 191, name: 'Flame yellowish orange'},
	{id: 192, name: 'Reddish brown'},
	{id: 193, name: 'Flame reddish orange'},
	{id: 194, name: 'Medium stone grey'},
	{id: 195, name: 'Royal blue'},
	{id: 196, name: 'Dark Royal blue'},
	{id: 198, name: 'Bright reddish lilac'},
	{id: 199, name: 'Dark stone grey'},
	{id: 200, name: 'Lemon metalic'},
	{id: 208, name: 'Light stone grey'},
	{id: 209, name: 'Dark Curry'},
	{id: 210, name: 'Faded green'},
	{id: 211, name: 'Turquoise'},
	{id: 212, name: 'Light Royal blue'},
	{id: 213, name: 'Medium Royal blue'},
	{id: 216, name: 'Rust'},
	{id: 217, name: 'Brown'},
	{id: 218, name: 'Reddish lilac'},
	{id: 219, name: 'Lilac'},
	{id: 220, name: 'Light lilac'},
	{id: 221, name: 'Bright purple'},
	{id: 222, name: 'Light purple'},
	{id: 223, name: 'Light pink'},
	{id: 224, name: 'Light brick yellow'},
	{id: 225, name: 'Warm yellowish orange'},
	{id: 226, name: 'Cool yellow'},
	{id: 232, name: 'Dove blue'},
	{id: 268, name: 'Medium lilac'},
	{id: 301, name: 'Slime green'},
	{id: 302, name: 'Smoky grey'},
	{id: 303, name: 'Dark blue'},
	{id: 304, name: 'Parsley green'},
	{id: 305, name: 'Steel blue'},
	{id: 306, name: 'Storm blue'},
	{id: 307, name: 'Lapis'},
	{id: 308, name: 'Dark indigo'},
	{id: 309, name: 'Sea green'},
	{id: 310, name: 'Shamrock'},
	{id: 311, name: 'Fossil'},
	{id: 312, name: 'Mulberry'},
	{id: 313, name: 'Forest green'},
	{id: 314, name: 'Cadet blue'},
	{id: 315, name: 'Electric blue'},
	{id: 316, name: 'Eggplant'},
	{id: 317, name: 'Moss'},
	{id: 318, name: 'Artichoke'},
	{id: 319, name: 'Sage green'},
	{id: 320, name: 'Ghost grey'},
	{id: 321, name: 'Lilac'},
	{id: 322, name: 'Plum'},
	{id: 323, name: 'Olivine'},
	{id: 324, name: 'Laurel green'},
	{id: 325, name: 'Quill grey'},
	{id: 327, name: 'Crimson'},
	{id: 328, name: 'Mint'},
	{id: 329, name: 'Baby blue'},
	{id: 330, name: 'Carnation pink'},
	{id: 331, name: 'Persimmon'},
	{id: 332, name: 'Maroon'},
	{id: 333, name: 'Gold'},
	{id: 334, name: 'Daisy orange'},
	{id: 335, name: 'Pearl'},
	{id: 336, name: 'Fog'},
	{id: 337, name: 'Salmon'},
	{id: 338, name: 'Terra Cotta'},
	{id: 339, name: 'Cocoa'},
	{id: 340, name: 'Wheat'},
	{id: 341, name: 'Buttermilk'},
	{id: 342, name: 'Mauve'},
	{id: 343, name: 'Sunrise'},
	{id: 344, name: 'Tawny'},
	{id: 345, name: 'Rust'},
	{id: 346, name: 'Cashmere'},
	{id: 347, name: 'Khaki'},
	{id: 348, name: 'Lily white'},
	{id: 349, name: 'Seashell'},
	{id: 350, name: 'Burgundy'},
	{id: 351, name: 'Cork'},
	{id: 352, name: 'Burlap'},
	{id: 353, name: 'Beige'},
	{id: 354, name: 'Oyster'},
	{id: 355, name: 'Pine Cone'},
	{id: 356, name: 'Fawn brown'},
	{id: 357, name: 'Hurricane grey'},
	{id: 358, name: 'Cloudy grey'},
	{id: 359, name: 'Linen'},
	{id: 360, name: 'Copper'},
	{id: 361, name: 'Dirt brown'},
	{id: 362, name: 'Bronze'},
	{id: 363, name: 'Flint'},
	{id: 364, name: 'Dark taupe'},
	{id: 365, name: 'Burnt Sienna'},
	{id: 1001, name: 'Institutional white'},
	{id: 1002, name: 'Mid gray'},
	{id: 1003, name: 'Really black'},
	{id: 1004, name: 'Really red'},
	{id: 1005, name: 'Deep orange'},
	{id: 1006, name: 'Alder'},
	{id: 1007, name: 'Dusty Rose'},
	{id: 1008, name: 'Olive'},
	{id: 1009, name: 'New Yeller'},
	{id: 1010, name: 'Really blue'},
	{id: 1011, name: 'Navy blue'},
	{id: 1012, name: 'Deep blue'},
	{id: 1013, name: 'Cyan'},
	{id: 1014, name: 'CGA brown'},
	{id: 1015, name: 'Magenta'},
	{id: 1016, name: 'Pink'},
	{id: 1017, name: 'Deep orange'},
	{id: 1018, name: 'Teal'},
	{id: 1019, name: 'Toothpaste'},
	{id: 1020, name: 'Lime green'},
	{id: 1021, name: 'Camo'},
	{id: 1022, name: 'Grime'},
	{id: 1023, name: 'Lavender'},
	{id: 1024, name: 'Pastel light blue'},
	{id: 1025, name: 'Pastel orange'},
	{id: 1026, name: 'Pastel violet'},
	{id: 1027, name: 'Pastel blue-green'},
	{id: 1028, name: 'Pastel green'},
	{id: 1029, name: 'Pastel yellow'},
	{id: 1030, name: 'Pastel brown'},
	{id: 1031, name: 'Royal purple'},
	{id: 1032, name: 'Hot pink'}
];

class EditorItemCard extends Component {
	constructor(props) {
		super(props);
		this.state = {
			worn: false
		};
		
		this.wearAssetId = this.wearAssetId.bind(this);
	}
	
	componentDidMount() {
		if(this.props.item.Wearing)
			this.setState({ worn: true });
	}
	
	wearAssetId(assetId) {
		this.setState({ worn: !this.state.worn });
		if(this.state.worn)
			this.props.unwearAssetId(assetId);
		else
			this.props.wearAssetId(assetId);
	}
	
	render() {
		var item = this.props.item;
		
		return (
			<div className="virtubrick-item-card virtubrick-avatar-card">
				<span className="card m-2">
					<a className="text-decoration-none text-reset" href={ item.Url }>
						<ProgressiveImage
							src={ item.Thumbnail } 
							placeholderImg={ buildGenericApiUrl('www', 'images/busy/asset.png') }
							alt={ item.Name }
							className='img-fluid'
						/>
						<div className="p-2 pb-0">
							<p className="text-truncate">{ item.Name }</p>
						</div>
					</a>
					<button className={classNames({'btn': true, 'btn-sm': true, 'btn-primary': !this.state.worn, 'btn-danger': this.state.worn, 'm-2': true,})} onClick={ () => this.wearAssetId(item.id) }>{ this.state.worn ? 'Take off' : 'Wear' }</button>
				</span>
			</div>
		);
	}
}

class WardrobeTab extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loaded: false,
			assetTabs: [
				{label: 'Heads', typeId: 17},
				{label: 'Faces', typeId: 18},
				{label: 'Hats', typeId: 8},
				{label: 'T-Shirts', typeId: 2},
				{label: 'Shirts', typeId: 11},
				{label: 'Pants', typeId: 12},
				{label: 'Gear', typeId: 19},
				{label: 'Torsos', typeId: 27},
				{label: 'Left Arms', typeId: 29},
				{label: 'Right Arms', typeId: 28},
				{label: 'Left Legs', typeId: 30},
				{label: 'Right Legs', typeId: 31},
				{label: 'Packages', typeId: 32}
			],
			pageKey: 0,
			pageNumber: 1,
			pages: 0,
			items: []
		};
		
		this.setTypeId = this.setTypeId.bind(this);
		this.incrementPage = this.incrementPage.bind(this);
		this.loadPage = this.loadPage.bind(this);
		this.refresh = this.refresh.bind(this);
	}
	
	componentDidMount() {
		this.setTypeId(8, true); // XlXi: Bypass needed, else the initial page load isn't going to happen.
	}
	
	setTypeId(assetTypeId, forceLoad = false) {
		this.setState({ selectedTypeId: assetTypeId}, function() {
			this.loadPage(1, !forceLoad);
		});
	}
	
	refresh() {
		this.loadPage(this.state.pageNumber);
	}
	
	incrementPage(amount) {
		this.loadPage(this.state.pageNumber + amount);
	}
	
	loadPage(pageNum, noBypass = true) {
		if(!this.state.loaded && noBypass)
			return;
		
		this.setState({ loaded: false });
		
		let oldPageNum = this.state.pageNumber;
		this.setState({ pageNumber: pageNum });
		
		axios.get(buildGenericApiUrl('api', `avatar/v1/list?assetTypeId=${this.state.selectedTypeId}${ pageNum > 1 ? '&page=' + pageNum : '' }`))
			.then(res => {
				const items = res.data;
				let newKey = this.state.pageKey + 1;
				
				this.setState({ pageKey: newKey, items: items.data, pages: items.pages, loaded: true });
			})
			.catch(() => {
				this.props.setError('Error loading wardrobe page.');
				this.setState({ pageNumber: oldPageNum, loaded: true });
			});
	}
	
	render() {
		return (
			<>
				<ul className="nav nav-pills my-2 mx-auto justify-content-center vb-wardrobe-nav">
					{
						this.state.assetTabs.map(({ label, typeId, ref }) => 
							<li className="nav-item">
								<button className={classNames({'nav-link': true, 'active': this.state.selectedTypeId == typeId, 'disabled': !this.state.loaded})} disabled={ !this.state.loaded } onClick={ () => this.setTypeId(typeId) }>{ label }</button>
							</li>
						)
					}
				</ul>
				{
					!this.state.loaded
					?
					<div className="virtubrick-shop-overlay">
						<Loader />
					</div>
					:
					null
				}
				{
					this.state.items.length == 0
					?
					<p className="text-muted text-center">Nothing found.</p>
					:
					<div key={ this.state.pageKey }>
						{
							this.state.items.map((item, index) =>
								<EditorItemCard unwearAssetId={ this.props.unwearAssetId } wearAssetId={ this.props.wearAssetId } item={ item } key={ index } />
							)
						}
					</div>
				}
				{
					this.state.pages > 1 ?
					<ul className="list-inline mx-auto mt-3">
						<li className="list-inline-item">
							<button className="btn btn-secondary" disabled={(this.state.pageNumber <= 1) ? true : null} onClick={ () => this.incrementPage(-1) }><i className="fa-solid fa-angle-left"></i></button>
						</li>
						<li className="list-inline-item virtubrick-paginator">
							<span>Page&nbsp;</span>
							<input type="text" value={ this.state.pageNumber || '' } className="form-control" disabled={this.state.loaded ? null : true} />
							<span>&nbsp;of { this.state.pages || '???' }</span>
						</li>
						<li className="list-inline-item">
							<button className="btn btn-secondary" disabled={(this.state.pageNumber >= this.state.pages) ? true : null} onClick={ () => this.incrementPage(1) }><i className="fa-solid fa-angle-right"></i></button>
						</li>
					</ul>
					:
					null
				}
			</>
		);
	}
}

class OutfitsTab extends Component {
	constructor(props) {
		super(props);
	}
	
	render() {
		return (<>
			<div className="mb-1 d-flex">
				<button className="btn btn-sm btn-primary ms-auto">Create New</button>
			</div>
			<p>outfits</p>
		</>);
	}
}

// TODO: XlXi: Move this out of this component. I was too lazy to do it initially but it can be done.
class BodyColorPaneBodyPart extends Component {
	constructor(props) {
		super(props);
		this.state = {
			color: 'vb-bc-194'
		}
		
		this.setColor = this.setColor.bind(this);
	}
	
	setColor(color) {
		this.setState({ color: color });
	}
	
	render() {
		return (
			<button
				className={ `vb-character vb-character-${ this.props.className } ${ this.state.color }` }
				onClick={ this.props.onClick }
			></button>
		)
	}
}

class BodyColorSelectionModal extends Component {
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
		
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="bc-tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new Bootstrap.Tooltip(tooltipTriggerEl)
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
						<div className="modal-header">
							<h5 className="modal-title">Set Color</h5>
							<button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div className="modal-body">
							<div className="vb-hex pb-5">
								<div className="vb-hex-container">
									{
										brickColors.map(({ id, name }) =>
											<div className={ `vb-bc-${ id }` } onClick={ () => this.props.setBodyColor(id) } data-bs-dismiss="modal" data-bs-toggle="bc-tooltip" data-bs-placement="top" title="" data-bs-original-title={ name }></div>
										)
									}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	}
}

class BodyColorPane extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loading: true,
			showModal: false
		};
		
		this.bodyParts = [
			{name: 'Head', className: 'head', ref: createRef()},
			{name: 'Torso', className: 'torso', ref: createRef()},
			{name: 'RightArm', className: 'limb', ref: createRef()},
			{name: 'LeftArm', className: 'limb', ref: createRef()},
			{name: 'RightLeg', className: 'limb', ref: createRef()},
			{name: 'LeftLeg', className: 'limb', ref: createRef()}
		];
		
		this.findPart = this.findPart.bind(this);
		this.setModal = this.setModal.bind(this);
		this.load = this.load.bind(this);
		this.openColorModal = this.openColorModal.bind(this);
		this.setBodyColor = this.setBodyColor.bind(this);
		this.getBodyPartElem = this.getBodyPartElem.bind(this);
	}
	
	findPart(name) {
		return this.bodyParts.find(obj => {
			return obj.name === name
		});
	}
	
	setModal(modal = null) {
		this.visibleModal = modal;
		
		if(modal) {
			this.setState({'showModal': true});
		} else {
			this.setState({'showModal': false});
		}
	}
	
	load() {
		this.setState({ loading: true });
		
		axios.get(buildGenericApiUrl('api', 'avatar/v1/body-color'))
			.then(res => {
				Object.keys(res.data.data).map(key => {
					let part = this.findPart(key);
					part.ref.current.setColor(`vb-bc-${ res.data.data[key] }`);
				});
				
				this.setState({ loading: false });
			})
			.catch(() => {
				this.props.setError('Error loading body colors pane.');
				this.setState({ loading: false });
			});
	}
	
	componentDidMount() {
		this.load();
	}
	
	openColorModal(name) {
		this.setModal(<BodyColorSelectionModal setModal={ this.setModal } setBodyColor={ (color) => this.setBodyColor(name, color) } />);
	}
	
	setBodyColor(name, color) {
		let part = this.findPart(name);
		part.ref.current.setColor(`vb-bc-${ color }`);
		
		this.props.setBodyColor(name, color);
	}
	
	getBodyPartElem(name) {
		let part = this.findPart(name);
		
		if(!part.elem)
		{
			let elem = createElement(
				BodyColorPaneBodyPart,
				{
					className: part.className,
					onClick: (() => this.openColorModal(part.name)),
					ref: part.ref,
					key: part.name
				}
			);
			part.elem = elem;
			return elem
		}
		
		return part.elem;
	}
	
	render() {
		return (
			<>
				{
					this.state.loading
					?
					<div className="virtubrick-shop-overlay">
						<Loader />
					</div>
					:
					null
				}
				
				{ this.getBodyPartElem('Head') }
				<div className="d-flex mx-auto">
					{ this.getBodyPartElem('RightArm') }
					{ this.getBodyPartElem('Torso') }
					{ this.getBodyPartElem('LeftArm') }
				</div>
				<div className="d-flex mx-auto">
					{ this.getBodyPartElem('RightLeg') }
					{ this.getBodyPartElem('LeftLeg') }
				</div>
				
				{ this.state.showModal ? this.visibleModal : null }
			</>
		);
	}
}

class WearingPane extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loading: true,
			items: [],
			pageKey: 0
		};
		
		this.load = this.load.bind(this);
	}
	
	componentDidMount() {
		this.load();
	}
	
	load() {
		this.setState({ loading: true });
		
		axios.get(buildGenericApiUrl('api', 'avatar/v1/wearing'))
			.then(res => {
				const items = res.data;
				let newKey = this.state.pageKey + 1;
				
				this.setState({ pageKey: newKey, items: items.data, loading: false });
			})
			.catch(() => {
				this.props.setError('Error loading wearing pane.');
				this.setState({ pageNumber: oldPageNum, loading: false });
			});
	}
	
	render() {
		return (
			<>
				{
					(this.state.loading)
					?
					<div className="virtubrick-shop-overlay">
						<Loader />
					</div>
					:
					null
				}
				{
					this.state.items.length == 0
					?
					<p className="text-muted text-center">Nothing found.</p>
					:
					<div key={ this.state.pageKey }>
						{
							this.state.items.map((item, index) =>
								<EditorItemCard unwearAssetId={ this.props.unwearAssetId } item={ item } key={ index } />
							)
						}
					</div>
				}
			</>
		);
	}
}

class AvatarEditor extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loading: false,
			thumbnailLoading: false,
			tabLoaded: false,
			tabs: [
				{label: 'Wardrobe', name: 'wardrobe', ref: createRef()},
				{label: 'Outfits', name: 'outfits', ref: createRef()}
			],
			error: null,
			thumbKey: 0
		};
		
		this.tabIndex = 0;
		
		this.wearingPane = createRef();
		this.bodyColorPane = createRef();
		this.tabPane = createRef();
		
		this.setCurrentTab = this.setCurrentTab.bind(this);
		this.setTab = this.setTab.bind(this);
		this.redrawCharacter = this.redrawCharacter.bind(this);
		this.reloadCharacter = this.reloadCharacter.bind(this);
		this.setError = this.setError.bind(this);
		this.thumbCallback = this.thumbCallback.bind(this);
		this.wearAssetId = this.wearAssetId.bind(this);
		this.unwearAssetId = this.unwearAssetId.bind(this);
		this.setBodyColor = this.setBodyColor.bind(this);
	}
	
	componentDidMount() {
		this.setTab('wardrobe');
	}
	
	setCurrentTab(instance)
	{
		this.currentTab = instance;
		this.setState({tabLoaded: true});
	}
	
	setTab(tabType) {
		this.setState({tabLoaded: false});
		this.tabIndex += 1;
		
		let component = {
			wardrobe: WardrobeTab,
			outfits: OutfitsTab
		}[tabType];
		
		this.setCurrentTab(createElement(
			component,
			{
				setError: this.setError,
				reloadCharacter: this.reloadCharacter,
				wearAssetId: this.wearAssetId,
				unwearAssetId: this.unwearAssetId,
				ref: this.tabPane,
				key: this.tabIndex
			}
		));
		
		this.state.tabs.map(({ name, ref }) => {
			if(name == tabType)
				ref.current.classList.add('active');
			else
				ref.current.classList.remove('active');
		});
	}
	
	redrawCharacter() {
		if(this.state.loading) return;
		
		this.setState({ loading: true });
		
		axios.post(buildGenericApiUrl('api', 'avatar/v1/redraw'))
			.then(res => {
				this.reloadCharacter();
				this.setState({ loading: false });
			})
			.catch(() => {
				this.setError('An error occurred while redrawing.');
				this.setState({ loading: false });
			});
	}
	
	setError(state) {
		this.setState({ error: state });
		setTimeout(function(){
			this.setState({ error: null });
		}.bind(this), 2000);
	}
	
	reloadCharacter() {
		let thumbKey = this.state.thumbKey + 1;
		
		this.wearingPane.current.load();
		this.bodyColorPane.current.load();
		this.tabPane.current.refresh();
		
		this.setState({ thumbKey: thumbKey });
	}
	
	thumbCallback(loading) {
		this.setState({ thumbnailLoading: loading });
	}
	
	wearAssetId(assetId, callback) {
		if(this.state.thumbnailLoading) return;
		
		axios.post(buildGenericApiUrl('api', `avatar/v1/wear?id=${ assetId }`))
			.then(res => {
				this.reloadCharacter();
				if(callback) callback();
			})
			.catch(() => {
				this.setError('An error occurred while trying to wear this item.');
			});
	}
	
	unwearAssetId(assetId, callback) {
		if(this.state.thumbnailLoading) return;
		
		axios.post(buildGenericApiUrl('api', `avatar/v1/unwear?id=${ assetId }`))
			.then(res => {
				this.reloadCharacter();
				if(callback) callback();
			})
			.catch(() => {
				this.setError('An error occurred while trying to take off this item.');
			});
	}
	
	setBodyColor(name, color) {
		if(this.state.thumbnailLoading) return;
		
		axios.post(buildGenericApiUrl('api', `avatar/v1/set-body-color?part=${ name }&color=${ color }`))
			.then(res => {
				this.reloadCharacter();
			})
			.catch(() => {
				this.setError('An error occurred while trying to change body color.');
			});
	}
	
	render() {
		return (
			<>
				{
					this.state.error
					?
					<div className="alert alert-danger virtubrick-alert virtubrick-error-popup">{ this.state.error }</div>
					:
					null
				}
				<div className="col-lg-3">
					<div className="card text-center vb-avatar-editor-card">
						{
							this.state.loading
							?
							<div className='position-absolute top-50 start-50 translate-middle'>
								<Loader />
							</div>
							:
							<ThumbnailTool element={ this.props.element } key={ this.state.thumbKey } placeholder="images/busy/user.png" thumbLoadCallback={ this.thumbCallback } />
						}
					</div>
					<p className="fst-italic">Is something wrong with your avatar?</p>
					<a className="text-decoration-none" href="#" onClick={ this.redrawCharacter }>Click here to re-draw it!</a>
					
					<h4 className="mt-3">Colors</h4>
					<div className="card p-4">
						<BodyColorPane setError={ this.setError } setBodyColor={ this.setBodyColor } ref={ this.bodyColorPane } />
					</div>
				</div>
				<div className="mt-lg-0 mt-4 col-lg-9">
					<ul className="nav nav-tabs">
						{
							this.state.tabs.map(({ label, name, ref }) => 
								<li className="nav-item">
									<button className="nav-link" onClick={ () => this.setTab(name) } ref={ ref }>{ label }</button>
								</li>
							)
						}
					</ul>
					<div className="card p-2">
						{ this.state.tabLoaded ? this.currentTab : <Loader /> }
					</div>
					
					<h4 className="mt-3">Currently Wearing</h4>
					<div className="card p-2">
						<WearingPane setError={ this.setError } reloadCharacter={ this.reloadCharacter } unwearAssetId={ this.unwearAssetId } ref={ this.wearingPane } />
					</div>
				</div>
			</>
		);
	}
}

export default AvatarEditor;