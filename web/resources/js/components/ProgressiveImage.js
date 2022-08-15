// https://levelup.gitconnected.com/react-lazy-load-image-e6a5ca944f32
// XlXi: i was too lazy to write this myself

import React, { useCallback, useEffect, useState } from "react";
export default ({ src, placeholderImg, ...props }) => {
	const [imgSrc, setSrc] = useState(placeholderImg || src);
	const onLoad = useCallback(() => {
		setSrc(src);
	}, [src]);
	
	useEffect(() => {
		const img = new Image();
		img.src = src;
		img.addEventListener("load", onLoad);
		
		return () => {
			img.removeEventListener("load", onLoad);
		};
	}, [src, onLoad]);
	
	return <img {...props} src={imgSrc} />;
};