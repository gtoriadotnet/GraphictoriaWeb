/*
	Graphictoria 5 (https://gtoria.net)
	Copyright © XlXi 2022
*/

import { Component, Suspense, useEffect, useRef } from 'react';

import * as THREE from 'three';
import { OBJLoader } from 'three/examples/jsm/loaders/OBJLoader';
import { MTLLoader } from 'three/examples/jsm/loaders/MTLLoader';
import { OrbitControls, PerspectiveCamera } from "@react-three/drei";
import { Canvas, useLoader, useThree } from '@react-three/fiber';
import { EffectComposer, SSAO } from '@react-three/postprocessing';
import { BlendFunction } from 'postprocessing';

import axios from 'axios';

import { buildGenericApiUrl } from '../util/HTTP.js';
import ProgressiveImage from './ProgressiveImage';
import Loader from './Loader';

axios.defaults.withCredentials = true;

const Scene = ({json}) => {
	const mtl = useLoader(MTLLoader, json.mtl);
	const obj = useLoader(OBJLoader, json.obj, (loader) => {
		mtl.preload();
		loader.setMaterials(mtl);
	});
	let controls = useRef();
	let midPoint;
	
	useThree(({camera, scene}) => {
		let aabbMax = json.AABB.max;
		let aabbMin = json.AABB.min;
		aabbMax = new THREE.Vector3(aabbMax.x, aabbMax.y, aabbMax.z);
		aabbMin = new THREE.Vector3(aabbMin.x, aabbMin.y, aabbMin.z);
		
		midPoint = new THREE.Vector3();
		midPoint.copy(aabbMax).add(aabbMin).multiplyScalar(0.5);
		
		let initialPosition = json.camera.position;
		let initialDirection = json.camera.direction;
		
		let thumbnailCameraPosition = new THREE.Vector3(initialPosition.x, initialPosition.y, initialPosition.z);
		let thumbnailCameraDirection = new THREE.Vector3(initialDirection.x, initialDirection.y, initialDirection.z);
		
		let pointToLookat = new THREE.Vector3();
		pointToLookat.copy(thumbnailCameraPosition);
		pointToLookat.sub(thumbnailCameraDirection);
		
		camera.position.set(thumbnailCameraPosition.x, thumbnailCameraPosition.y, thumbnailCameraPosition.z);
		camera.lookAt(pointToLookat);
		
		// lighting
		// FIXME: XlXi: if you toggle 3d on and off it'll create these twice
		let ambient = new THREE.AmbientLight(0x878780);
		scene.add(ambient);
		
		let sunLight = new THREE.DirectionalLight(0xacacac);
		sunLight.position.set(0.671597898, 0.671597898, -0.312909544).normalize();
		scene.add(sunLight);
		
		let backLight = new THREE.DirectionalLight(0x444444);
		let backLightPos = new THREE.Vector3()
			.copy(sunLight.position)
			.negate()
			.normalize(); // inverse of sun direction
		backLight.position.set(backLightPos);
		scene.add(backLight);
	});
	
	useEffect(() => {
		controls.current.target = midPoint;
		controls.current.update();
	});
	
	return (
		<>
			<primitive object={obj} />
			{/*
			<EffectComposer>
				<SSAO
					blendFunction={BlendFunction.MULTIPLY}
					samples={128}
					radius={2}
					intensity={60}
				/>
			</EffectComposer>
			*/}
			<OrbitControls makeDefault ref={controls} enableDamping={false} enablePan={false} autoRotate={true} />
		</>
	)
}

class ThumbnailTool extends Component {
	constructor(props) {
		super(props);
		this.state = {
			initialLoading: true,
			loading: false,
			is3d: false,
			seed3d: 0
		};
		
		this.tryAsset = this.tryAsset.bind(this);
		this.loadThumbnail = this.loadThumbnail.bind(this);
		this.toggle3D = this.toggle3D.bind(this);
	}
	
	componentDidMount() {
		let thumbnailElement = this.props.element;
		if (thumbnailElement) {
			this.thumbnail2d = thumbnailElement.getAttribute('data-asset-thumbnail-2d');
			this.assetId = thumbnailElement.getAttribute('data-asset-id');
			this.assetName = thumbnailElement.getAttribute('data-asset-name');
			this.wearable = Boolean(thumbnailElement.getAttribute('data-wearable'));
			this.renderable3d = Boolean(thumbnailElement.getAttribute('data-renderable3d'));
			
			this.setState({ initialLoading: false });
			
			if(this.renderable3d && localStorage.getItem('gt-use-3d-thumbnails') === 'true')
				this.toggle3D();
		}
	}
	
	loadThumbnail(url, is3d) {
		axios.get(buildGenericApiUrl('api', url))
			.then(res => {
				let data = res.data;
				
				if(data.status === 'success') {
					if(is3d) {
						axios.get(data.data)
							.then(res => {
								let newJson = res.data;
								newJson.mtl = buildGenericApiUrl('cdn', newJson.mtl);
								newJson.obj = buildGenericApiUrl('cdn', newJson.obj);
								if(Array.isArray(newJson.textures)) {
									let newTextures = [];
									newJson.textures.map((hash) => {
										newTextures.push(buildGenericApiUrl('cdn', hash));
									});
									newJson.textures = newTextures;
								} else {
									newJson.textures = buildGenericApiUrl('cdn', newJson.textures);
								}
								
								this.setState({ loading: false, json3d: res.data });
							});
					} else {
						this.setState({ loading: false });
					}
				} else {
					let lt = this.loadThumbnail;
					setTimeout(function(){lt(url,is3d)}, 1000);
				}
			});
	}
	
	tryAsset() {
		let is3d = !this.state.is3d;
		
		this.setState({ loading: true, is3d: is3d });
		this.loadThumbnail(`thumbnails/v1/try-asset?id=${this.assetId}&type=${this.state.is3d ? '3D' : '2D'}`, is3d);
	}
	
	toggle3D() {
		let is3d = !this.state.is3d;
		
		this.setState({ loading: true, is3d: is3d, seed3d: Math.random() });
		localStorage.setItem('gt-use-3d-thumbnails', is3d);
		
		if(is3d) {
			this.loadThumbnail(`thumbnails/v1/asset?id=${this.assetId}&type=3D`, true);
		} else {
			this.setState({ loading: false });
		}
	}
	
	render() {
		return (
			<>
			{
				this.state.initialLoading
				?
				<div className='position-absolute top-50 start-50 translate-middle'>
					<Loader />
				</div>
				:
				<>
					{
						this.state.loading
						?
						<div className='position-absolute top-50 start-50 translate-middle'>
							<Loader />
						</div>
						:
						(
							this.state.is3d
							?
							<Canvas key={ this.state.seed3d }>
								<Suspense fallback={null}>
									<Scene json={ this.state.json3d } />
								</Suspense>
							</Canvas>
							:
							<ProgressiveImage
								src={ this.thumbnail2d }
								placeholderImg={ buildGenericApiUrl('www', (this.props.placeholder == null ? 'images/busy/asset.png' : this.props.placeholder)) }
								alt={ this.assetName }
								className='img-fluid'
								width={ this.props.width }
								height={ this.props.height }
							/>
						)
					}
					{ this.wearable || this.renderable3d ?
					<div className='d-flex position-absolute bottom-0 end-0 pb-2 pe-2'>
						{
							this.wearable ?
							<button className='btn btn-secondary me-2' onClick={ this.tryAsset } disabled={ this.state.loading }>Try On</button>
							:
							null
						}
						{
							this.renderable3d ?
							<button className='btn btn-secondary' onClick={ this.toggle3D } disabled={ this.state.loading }>{ this.state.is3d ? '2D' : '3D' }</button>
							:
							null
						}
					</div>
					:
					null
					}
				</>
			}
			</>
		);
	}
}

export default ThumbnailTool;