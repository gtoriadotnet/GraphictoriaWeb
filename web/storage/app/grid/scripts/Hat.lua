local assetUrl, fileExtension, x, y, baseUrl = ...

pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end)
game:GetService("ContentProvider"):SetThreadPool(16)
game:GetService("ScriptContext").ScriptsDisabled = true 
settings()["Task Scheduler"].ThreadPoolConfig = Enum.ThreadPoolConfig.PerCore4;

local Lighting = game:GetService("Lighting")
Lighting.ClockTime = 13
Lighting.GeographicLatitude = -5

local accoutrement = game:GetObjects(assetUrl)[1]
accoutrement.Parent = workspace

return game:GetService("ThumbnailGenerator"):Click(fileExtension, x, y, --[[hideSky = ]] true, --[[crop = ]] true)
