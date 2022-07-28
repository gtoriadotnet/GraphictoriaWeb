// © XlXi 2022
// Graphictoria 5

import axios from 'axios';

import $ from 'jquery';

import * as ReactDOM from 'react-dom';
import React, { useRef, Suspense } from 'react';

import { buildGenericApiUrl } from '../util/HTTP.js';

import { Canvas, useFrame } from '@react-three/fiber';
import { Instances, Instance, PerspectiveCamera, useGLTF } from '@react-three/drei';

axios.defaults.withCredentials = true;

const randomVector = (r) => [r / 2 - Math.random() * r, r / 2 - Math.random() * r, r / 2 - Math.random() * r];
const randomEuler = () => [Math.random() * Math.PI, Math.random() * Math.PI, Math.random() * Math.PI];
const randomData = Array.from({ length: 2000 }, (r = 200) => ({ random: Math.random(), position: randomVector(r), rotation: randomEuler() }));

function Scene() {
	const { nodes, materials } = useGLTF('/models/graphictoriapart.glb');
	
	return (
		<>
			<ambientLight />
			<pointLight position={[10, 10, 10]} />
			<Instances range={2000} material={materials.Material} geometry={nodes.Cube.geometry} >
				{
					randomData.map((props, i) => (
						<Box key={i} {...props} />
					))
				}
			</Instances>
			<Camera makeDefault />
		</>
	);
}

function Box({ random, ...props }){
	const ref = useRef()
	useFrame((state) => {
		const t = state.clock.getElapsedTime() + random * 10000
		ref.current.rotation.set(Math.cos(t / 4) / 2, Math.sin(t / 4) / 2, Math.cos(t / 1.5) / 2)
		ref.current.position.y = Math.sin(t / 1.5) / 2
	});
	return (
		<group {...props}>
			<Instance ref={ref} />
		</group>
	)
}

function Camera({ ...props }){
	const ref = useRef()
	useFrame((state) => {
		const t = state.clock.getElapsedTime() / 30
		ref.current.position.x = 10 * Math.cos(t);
		ref.current.position.y = 4 * Math.sin(t);
		ref.current.position.z = 10 * Math.sin(t);
		ref.current.lookAt(0, 0, 0);
	});
	return (
		<PerspectiveCamera ref={ref} {...props} />
	)
}

let ButtonHistory = []

function attemptBypass() {
	axios.post(buildGenericApiUrl('apis', 'v1/maintenance/bypass'), {
			'password': $('#gt_mt_buttons > input').val(),
			'buttons': ButtonHistory.slice(-40)
		})
		.then((response) => {
			window.location.reload();
		});
}

$(document).ready(function() {
	ReactDOM.render(
		(<Canvas>
			<Suspense fallback={null}>
				<Scene />
			</Suspense>
		</Canvas>),
		document.getElementsByClassName('gtoria-maintenance-background')[0]
	);
	
	$('#gt_mt_buttons').on('click', 'button', function() {
		let ButtonId = parseInt(this.getAttribute('name').substr(8)); //gt_mtbtnX
		
		ButtonHistory.push(ButtonId);
		attemptBypass();
	});
});