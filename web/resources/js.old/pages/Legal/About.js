// © XlXi 2021
// Graphictoria 5

import React from "react";

import SetTitle from "../../Helpers/Title.js";
import { Legal } from "../Templates/Legal.js";

let Sections = [
	{
		id: 1,
		title: "rewrite dis",
		content: (
			<p>ye</p>
		)
	}
];

class About extends React.Component {
	componentDidMount()
	{
		SetTitle("About Us");
	}
	
	render()
	{
		return (<Legal name="About Us" purpose="go over who we are and what our purpose is" lastModified={1626047792664} sections={Sections}/>);
	}
}

export { About };