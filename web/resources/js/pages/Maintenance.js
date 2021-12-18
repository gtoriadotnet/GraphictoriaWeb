// © XlXi 2021
// Graphictoria 5

import React, { useRef, Suspense } from 'react';

import { Canvas, useFrame } from '@react-three/fiber';
import { Instances, Instance, PerspectiveCamera, useGLTF } from '@react-three/drei';

import SetTitle from '../Helpers/Title.js';

const randomVector = (r) => [r / 2 - Math.random() * r, r / 2 - Math.random() * r, r / 2 - Math.random() * r];
const randomEuler = () => [Math.random() * Math.PI, Math.random() * Math.PI, Math.random() * Math.PI];
const randomData = Array.from({ length: 2000 }, (r = 200) => ({ random: Math.random(), position: randomVector(r), rotation: randomEuler() }));

let Buttons = [];
let ButtonsAlreadyTemplated = false;

function MakeButtons()
{
	if(!ButtonsAlreadyTemplated)
	{
		ButtonsAlreadyTemplated = true;
		let name = 'Graphictoria';
		
		for (var i = 0; i < name.length; i++) {
			Buttons.push({id: i, value: name.charAt(i)});
		}
	}
}

function DoButton(position)
{
	console.log(position);
}

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

class Maintenance extends React.Component {
	
	componentDidMount()
	{
		SetTitle("Maintenance");
	}
	
	render()
	{
		MakeButtons();
		return (
			<>
				<div className="gtoria-maintenance-background">
					<Canvas>
						<Suspense fallback={null}>
							<Scene />
						</Suspense>
					</Canvas>
				</div>
				<div className="text-center mt-auto container gtoria-maintenance-form">
					<h1>Graphictoria is currently under maintenance.</h1>
					<h4>Our cyborg team of highly trained code-monkes are working to make Graphictoria better. We'll be back soon!</h4>
					<div className="input-group mt-5">
						<input type="password" className="form-control" placeholder="Password" autoComplete="off"/>
						{
							Buttons.map(character => (
								<React.Fragment key={character.id}>
									<button className="btn btn-secondary" type="button" onClick={ () => DoButton(character.id) }>{character.value}</button>
								</React.Fragment>
							))
						}
					</div>
				</div>
			</>
		);
	}
}

export { Maintenance };