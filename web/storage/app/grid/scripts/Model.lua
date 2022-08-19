local assetUrl, fileExtension, x, y, baseUrl = ...

pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end)
game:GetService("ContentProvider"):SetThreadPool(16)
game:GetService("ScriptContext").ScriptsDisabled = true 
settings()["Task Scheduler"].ThreadPoolConfig = Enum.ThreadPoolConfig.PerCore4;

local Lighting = game:GetService("Lighting")
Lighting.ClockTime = 13
Lighting.GeographicLatitude = -5

local ThumbnailGenerator = game:GetService("ThumbnailGenerator")
 
 for _, object in pairs(game:GetObjects(assetUrl)) do
 	if object:IsA("Sky") then
		local resultValues = nil
		local success = pcall(function() resultValues = {ThumbnailGenerator:ClickTexture(object.SkyboxFt, fileExtension, x, y)} end)
		if success then
			return unpack(resultValues)
		else
			object.Parent = game:GetService("Lighting")
			return ThumbnailGenerator:Click(fileExtension, x, y, --[[hideSky = ]] false)
		end
 	elseif object:IsA("LuaSourceContainer") then
 		return ThumbnailGenerator:ClickTexture(baseUrl.. "Thumbs/Script.png", fileExtension, x, y)
 	elseif object:IsA("SpecialMesh") then
 		local part = Instance.new("Part")
 		part.Parent = workspace
 		object.Parent = part
 		return ThumbnailGenerator:Click(fileExtension, x, y, --[[hideSky = ]] true)
 	else
 		pcall(function() object.Parent = workspace end)
 	end
 end
 
 return ThumbnailGenerator:Click(fileExtension, x, y, --[[hideSky = ]] true)
