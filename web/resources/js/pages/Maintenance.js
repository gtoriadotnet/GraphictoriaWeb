// © XlXi 2021
// Graphictoria 5

import React from "react";

import SetTitle from "../Helpers/Title.js";

let Buttons = [];
let ButtonsAlreadyTemplated = false;

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
		);
	}
}

export { Maintenance };