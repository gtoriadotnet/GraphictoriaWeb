// © XlXi 2021
// Graphictoria 5

import React, { useRef } from 'react';

import { Canvas, useFrame } from '@react-three/fiber';

import SetTitle from "../Helpers/Title.js";

let Buttons = [];
let ButtonsAlreadyTemplated = false;

function Box(props) {
	const ref = useRef();
	
	const distanceFromCamera = Math.random() * 30
	
	const rotX = (Math.random() * 200 - 100)/300
	const rotZ = (Math.random() * 200 - 100)/300
	
	useFrame((state, delta) => {
		ref.current.position.y -= 0.6 * delta
		ref.current.rotation.x += rotX * delta
		ref.current.rotation.z += rotZ * delta
		
		if(ref.current.position.y < -30)
		{
			ref.current.position.y = 30;
		}
	});
	
	return (
		<mesh
			{...props}
			ref={ref}
			scale={1}
			position={[(Math.random() * 120) - 60, (Math.random() * 60) - 30, -distanceFromCamera]}
			rotation={[Math.random() * 360, Math.random() * 360, Math.random() * 360]}>
			<boxGeometry args={[1, 0.3, 0.5]} />
			<meshStandardMaterial color={'grey'} />
		</mesh>
	)
}

function MakeButtons()
{
	if(!ButtonsAlreadyTemplated)
	{
		ButtonsAlreadyTemplated = true;
		let name = "Graphictoria";
		
		for (var i = 0; i < name.length; i++) {
			Buttons.push({id: i, value: name.charAt(i)});
		}
	}
}

function DoButton(position)
{
	console.log(position);
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
						<ambientLight />
						<pointLight position={[10, 10, 10]} />
						{
							[...Array(100)].map((e, i) => (<Box key={i}/>))
						}
					</Canvas>
				</div>
				<div className="text-center mt-auto container">
					<h1>Graphictoria is currently under maintenance.</h1>
					<h4>Our advanced team of cyber monkes are working to make Graphictoria better. We'll be back soon!</h4>
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