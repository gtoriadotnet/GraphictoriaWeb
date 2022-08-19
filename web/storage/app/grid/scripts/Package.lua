local assetUrls, fileExtension, x, y, baseUrl, rigUrl, customTextureUrls = ...

pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end)
game:GetService("ContentProvider"):SetThreadPool(16)
game:GetService("ScriptContext").ScriptsDisabled = true 
settings()["Task Scheduler"].ThreadPoolConfig = Enum.ThreadPoolConfig.PerCore4;

local Lighting = game:GetService("Lighting")
Lighting.ClockTime = 13
Lighting.GeographicLatitude = -5

function split(str, delim)
    local results = {}
    local lastMatchEnd = 0
    
    local matchStart, matchEnd = string.find(str, delim, --[[init = ]] 1, --[[plain = ]] true)
    while matchStart and matchEnd do
        if matchStart - lastMatchEnd > 1 then
            table.insert(results, string.sub(str, lastMatchEnd + 1, matchStart - 1))
        end

        lastMatchEnd = matchEnd
        matchStart, matchEnd = string.find(str, delim, --[[init = ]] lastMatchEnd + 1, --[[plain = ]] true)
    end

    if string.len(str) - lastMatchEnd > 1 then
        table.insert(results, string.sub(str, lastMatchEnd + 1))
    end
    return results
end

local mannequin = game:GetObjects(rigUrl)[1]
mannequin.Humanoid.DisplayDistanceType = Enum.HumanoidDisplayDistanceType.None
mannequin.Parent = workspace

local accoutrements = {}

local assetUrlsList = split(assetUrls, ";")
for _, assetUrl in pairs(assetUrlsList) do
	local currObject = game:GetObjects(assetUrl)[1]
    
    if currObject:IsA("Tool") then
        annequin.Torso["Right Shoulder"].CurrentAngle = math.rad(90)
        currObject.Parent = mannequin
    elseif currObject:IsA("DataModelMesh") then
        local headMesh = mannequin.Head:FindFirstChild("Mesh")
        if headMesh then
            headMesh:Destroy()
        end
        currObject.Parent = mannequin.Head
    elseif currObject:IsA("Decal") then
        local face = mannequin.Head:FindFirstChild("face")
        if face then
            face:Destroy()
        end
        currObject.Parent = mannequin.Head
    elseif currObject:IsA("Accoutrement") then
        table.insert(accoutrements, currObject)
    else
        currObject.Parent = mannequin
    end    
end

local textureUrls = split(customTextureUrls, ";")
for _, url in pairs(textureUrls) do
    local obj = game:GetObjects(url)[1]
    if obj:IsA("Shirt") then
        -- Don't add a texture Shirt if package already has a Shirt
        if not mannequin:FindFirstChildOfClass("Shirt") then
            obj.Parent = mannequin
        end
    elseif obj:IsA("Pants") then
        -- Don't add a texture Pants if package already has a Pants
        if not mannequin:FindFirstChildOfClass("Pants") then
            obj.Parent = mannequin
        end
    else
        obj.Parent = mannequin
    end
end

function findFirstMatchingAttachment(model, name)
    for _, child in pairs(model:GetChildren()) do
        if child:IsA("Attachment") and child.Name == name then
            return child
        elseif not child:IsA("Accoutrement") and not child:IsA("Tool") then
            local foundAttachment = findFirstMatchingAttachment(child, name)
            if foundAttachment then
                return foundAttachment
            end
        end
    end
end

for _, accoutrement in pairs(accoutrements) do
    local handle = accoutrement:FindFirstChild("Handle")
    if handle then
        local accoutrementAttachment = handle:FindFirstChildOfClass("Attachment")
        local characterAttachment = nil
        if accoutrementAttachment then
            characterAttachment = findFirstMatchingAttachment(mannequin, accoutrementAttachment.Name)
        end

        local attachmentPart = nil
        if characterAttachment then
            attachmentPart = characterAttachment.Parent
        else
            attachmentPart = mannequin:FindFirstChild("Head")
        end

        local attachmentCFrame = nil
        if characterAttachment then
            attachmentCFrame = characterAttachment.CFrame
        else
            attachmentCFrame = CFrame.new(0, 0.5, 0)
        end

        local hatCFrame = nil
        if accoutrementAttachment then
            hatCFrame = accoutrementAttachment.CFrame
        else
            hatCFrame = accoutrement.AttachmentPoint
        end

        handle.CFrame = attachmentPart.CFrame * attachmentCFrame * hatCFrame:inverse()
        handle.Anchored = true
        handle.Parent = mannequin
    end
end

return game:GetService("ThumbnailGenerator"):Click(fileExtension, x, y, --[[hideSky = ]] true)