// © XlXi 2021
// Graphictoria 5

import React from "react";

import SetTitle from "../../Helpers/Title.js";
import { Legal } from "../Templates/Legal.js";

let Sections = [
	{
		id: 1,
		title: "test1",
		content: (<><p>test 1</p></>)
	},
	{
		id: 2,
		title: "test2",
		content: (<><p>test 2, l<b>ol</b>.</p></>)
	},
	{
		id: 3,
		title: "the third test",
		content: (<><p>cock</p></>)
	}
];

class Privacy extends React.Component {
	componentDidMount()
	{
		SetTitle("Privacy");
	}
	
	render()
	{
		return (<Legal name="Privacy" purpose="go over how we handle user privacy" lastModified={1624499439860} sections={Sections}/>);
	}
}

export { Privacy };